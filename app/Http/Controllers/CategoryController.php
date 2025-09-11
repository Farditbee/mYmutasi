<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    public function getPemasukan()
    {
        $categories = Category::active()->where('type', 'income')->get(['id', 'name']);

        return response()->json($categories);
    }

    public function getPengeluaran()
    {
        $categories = Category::active()->where('type', 'expense')->get(['id', 'name']);

        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        Category::create($validated);
        
        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $transactionCount = $category->transactions()->count();
        $totalAmount = $category->transactions()->sum('amount');
        
        return view('categories.show', compact('category', 'transactionCount', 'totalAmount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $category->update($validated);
        
        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Check if category has transactions
        if ($category->transactions()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki transaksi.');
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Toggle the active status of a category.
     */
    public function toggleStatus(Category $category): RedirectResponse
    {
        $category->update(['is_active' => !$category->is_active]);
        
        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('categories.index')
            ->with('success', "Kategori berhasil {$status}.");
    }
}