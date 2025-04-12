<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // Fetch all categories
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        // Return the view to create a new category
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|unique:categories,name',
            'status' => 'required|in:0,1' // Ensuring status is either 0 or 1 (inactive or active)
        ]);

        // Create a new category
        Category::create([
            'name' => $request->name,
            'status' => $request->status, // Store the status
        ]);

        // Redirect back with success message
        return redirect()->route('categories.index')->with('success', 'Category added successfully.');
    }

    public function edit(Category $category)
    {
        // Return the view to edit the category
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id, // Ignore the current category when checking for uniqueness
            'status' => 'required|in:0,1' // Ensuring status is either 0 or 1 (inactive or active)
        ]);

        // Update the category with the new name and status
        $category->update([
            'name' => $request->name,
            'status' => $request->status, // Update the status
        ]);

        // Redirect back with success message
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Delete the category
        $category->delete();

        // Redirect back with success message
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
