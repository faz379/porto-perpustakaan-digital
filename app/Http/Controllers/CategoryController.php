<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('created_at', 'asc')->paginate(5);

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:categories',
        ]);

        // Laravel akan otomatis mengisi created_at dan updated_at
        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        // Directly use the injected $category instance
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
    // Validate the request
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id,
        ]);

    // Update the category
        $category->update([
            'name' => $request->name,
        ]);

    // Redirect to the index page with a success message
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function show(Category $category): View
    {
        return view('categories.show', compact('category'));
    }
    
}
