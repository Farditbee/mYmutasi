<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = $user->transactions()->with('category')->latest('transaction_date');
        
        // Filter by type if specified
        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }
        
        // Filter by category if specified
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by date range if specified
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }
        
        // Handle pagination per page
        $perPage = $request->get('per_page', 10);
        
        if (!in_array($perPage, [10, 15, 25, 50])) {
            $perPage = 10;
        }
        
        $transactions = $query->paginate($perPage);
        
        // Append query parameters to pagination links
        $transactions->appends($request->query());
        
        // Calculate totals
        $totalIncome = $user->transactions()->income()->sum('amount');
        $totalExpense = $user->transactions()->expense()->sum('amount');
        $balance = $totalIncome - $totalExpense;
        
        $categories = Category::active()->get();
        
        return view('transactions.index', compact('transactions', 'totalIncome', 'totalExpense', 'balance', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Calculate totals for balance display
        $totalIncome = $user->transactions()->income()->sum('amount');
        $totalExpense = $user->transactions()->expense()->sum('amount');
        $balance = $totalIncome - $totalExpense;
        
        return view('transactions.create', compact('totalIncome', 'totalExpense', 'balance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);
        
        $category = Category::find($validated['category_id']);
        if ($category->type !== $validated['type']) {
            return back()->withErrors(['category_id' => 'Kategori tidak sesuai dengan jenis transaksi.'])->withInput();
        }
        
        $validated['user_id'] = Auth::id();
        
        Transaction::create($validated);
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): View
    {
        // Ensure the transaction belongs to the authenticated user
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction): View
    {
        // Ensure the transaction belongs to the authenticated user
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Calculate totals for balance display
        $totalIncome = $user->transactions()->income()->sum('amount');
        $totalExpense = $user->transactions()->expense()->sum('amount');
        $balance = $totalIncome - $totalExpense;
        
        $categories = Category::active()->get();
        $incomeCategories = $categories->where('type', 'income')->values();
        $expenseCategories = $categories->where('type', 'expense')->values();
        
        return view('transactions.edit', compact('transaction', 'categories', 'incomeCategories', 'expenseCategories', 'totalIncome', 'totalExpense', 'balance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        // Ensure the transaction belongs to the authenticated user
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);
        
        // Verify that the category type matches the transaction type
        $category = Category::find($validated['category_id']);
        if ($category->type !== $validated['type']) {
            return back()->withErrors(['category_id' => 'Kategori tidak sesuai dengan jenis transaksi.'])->withInput();
        }
        
        $transaction->update($validated);
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        // Ensure the transaction belongs to the authenticated user
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        
        $transaction->delete();
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Get categories by type (AJAX endpoint).
     */
    public function getCategoriesByType(Request $request)
    {
        $type = $request->get('type');
        
        if (!in_array($type, ['income', 'expense'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }
        
        $categories = Category::active()->where('type', $type)->get(['id', 'name', 'color']);
        
        return response()->json($categories);
    }

    /**
     * Display reports overview.
     */
    public function reports(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get selected month from request or use current month
        $selectedMonth = $request->get('month', now()->format('Y-m'));
        $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth);
        
        // Get selected year from request or use current year
        $selectedYear = $request->get('year', now()->year);
        $yearDate = \Carbon\Carbon::createFromDate($selectedYear);
        
        // Daily statistics (today)
        $today = now()->format('Y-m-d');
        $dailyTransactions = $user->transactions()->whereDate('transaction_date', $today)->get();
        $dailyIncome = $dailyTransactions->where('type', 'income')->sum('amount');
        $dailyExpense = $dailyTransactions->where('type', 'expense')->sum('amount');
        $dailyBalance = $dailyIncome - $dailyExpense;
        
        // Monthly statistics (selected month)
        $monthlyTransactions = $user->transactions()
            ->whereYear('transaction_date', $monthDate->year)
            ->whereMonth('transaction_date', $monthDate->month)
            ->get();
        $monthlyIncome = $monthlyTransactions->where('type', 'income')->sum('amount');
        $monthlyExpense = $monthlyTransactions->where('type', 'expense')->sum('amount');
        $monthlyBalance = $monthlyIncome - $monthlyExpense;
        
        // Overall statistics
        $totalIncome = $user->transactions()->income()->sum('amount');
        $totalExpense = $user->transactions()->expense()->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;
        
        // Chart data - Selected month daily (30/31 days)
        $daysInMonth = $monthDate->daysInMonth;
        $monthlyDailyData = collect();
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $monthDate->copy()->day($day);
            $dayIncome = $user->transactions()
                ->income()
                ->whereDate('transaction_date', $date)
                ->sum('amount');
            $dayExpense = $user->transactions()
                ->expense()
                ->whereDate('transaction_date', $date)
                ->sum('amount');
            
            $monthlyDailyData->push([
                'day' => $day,
                'date' => $date->format('d'),
                'income' => $dayIncome,
                'expense' => $dayExpense,
                'balance' => $dayIncome - $dayExpense
            ]);
        }
        
        // Chart data - 12 months based on selected year
        $last12Months = collect();
        for ($i = 1; $i <= 12; $i++) {
            $date = $yearDate->copy()->month($i);
            $monthIncome = $user->transactions()
                ->income()
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');
            $monthExpense = $user->transactions()
                ->expense()
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');
            
            $last12Months->push([
                'month' => $date->format('M Y'),
                'income' => $monthIncome,
                'expense' => $monthExpense
            ]);
        }
        
        // If AJAX request, return JSON data
        if ($request->ajax()) {
            // Check if it's a year filter request
            if ($request->has('year')) {
                return response()->json([
                    'yearlyData' => [
                        'categories' => $last12Months->pluck('month')->toArray(),
                        'series' => [
                            [
                                'name' => 'Pemasukan',
                                'data' => $last12Months->pluck('income')->toArray()
                            ],
                            [
                                'name' => 'Pengeluaran',
                                'data' => $last12Months->pluck('expense')->toArray()
                            ]
                        ]
                    ],
                    'selectedYear' => $selectedYear
                ]);
            }
            
            // Month filter request
            return response()->json([
                'dailyData' => [
                    'categories' => $monthlyDailyData->pluck('date')->toArray(),
                    'series' => [
                        [
                            'name' => 'Pemasukan',
                            'data' => $monthlyDailyData->pluck('income')->toArray()
                        ],
                        [
                            'name' => 'Pengeluaran',
                            'data' => $monthlyDailyData->pluck('expense')->toArray()
                        ]
                    ]
                ],
                'selectedMonthName' => $monthDate->format('F Y')
            ]);
        }
        
        return view('reports.index', compact(
            'dailyIncome', 'dailyExpense', 'dailyBalance',
            'monthlyIncome', 'monthlyExpense', 'monthlyBalance',
            'totalIncome', 'totalExpense', 'totalBalance',
            'monthlyDailyData', 'last12Months', 'selectedMonth', 'selectedYear'
        ));
    }

    /**
     * Display daily report.
     */
    public function dailyReport(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get date from request or use today
        $date = $request->get('date', now()->format('Y-m-d'));
        
        // Get transactions for the specific date
        $transactions = $user->transactions()
            ->with('category')
            ->whereDate('transaction_date', $date)
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        // Calculate totals for the day
        $dailyIncome = $transactions->where('type', 'income')->sum('amount');
        $dailyExpense = $transactions->where('type', 'expense')->sum('amount');
        $dailyBalance = $dailyIncome - $dailyExpense;
        
        // Calculate overall totals
        $totalIncome = $user->transactions()->income()->sum('amount');
        $totalExpense = $user->transactions()->expense()->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;
        
        return view('reports.daily', compact(
            'transactions', 
            'date', 
            'dailyIncome', 
            'dailyExpense', 
            'dailyBalance',
            'totalIncome',
            'totalExpense',
            'totalBalance'
        ));
    }

    /**
     * Display monthly report.
     */
    public function monthlyReport(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get month and year from request or use current month
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));
        
        // Get transactions for the specific month
        $transactions = $user->transactions()
            ->with('category')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        // Calculate totals for the month
        $monthlyIncome = $transactions->where('type', 'income')->sum('amount');
        $monthlyExpense = $transactions->where('type', 'expense')->sum('amount');
        $monthlyBalance = $monthlyIncome - $monthlyExpense;
        
        // Group transactions by date for daily breakdown
        $dailyBreakdown = $transactions->groupBy(function($transaction) {
            return $transaction->transaction_date->format('Y-m-d');
        })->map(function($dayTransactions) {
            return [
                'income' => $dayTransactions->where('type', 'income')->sum('amount'),
                'expense' => $dayTransactions->where('type', 'expense')->sum('amount'),
                'balance' => $dayTransactions->where('type', 'income')->sum('amount') - $dayTransactions->where('type', 'expense')->sum('amount'),
                'count' => $dayTransactions->count()
            ];
        });
        
        // Calculate overall totals
        $totalIncome = $user->transactions()->income()->sum('amount');
        $totalExpense = $user->transactions()->expense()->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;
        
        return view('reports.monthly', compact(
            'transactions', 
            'month', 
            'year',
            'monthlyIncome', 
            'monthlyExpense', 
            'monthlyBalance',
            'dailyBreakdown',
            'totalIncome',
            'totalExpense',
            'totalBalance'
        ));
    }
}