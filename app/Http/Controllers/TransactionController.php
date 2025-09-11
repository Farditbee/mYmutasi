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
        
        $transactions = $query->paginate(15);
        
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
        return view('transactions.create');
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
        
        $categories = Category::active()->get();
        $incomeCategories = $categories->where('type', 'income')->values();
        $expenseCategories = $categories->where('type', 'expense')->values();
        
        return view('transactions.edit', compact('transaction', 'categories', 'incomeCategories', 'expenseCategories'));
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
}