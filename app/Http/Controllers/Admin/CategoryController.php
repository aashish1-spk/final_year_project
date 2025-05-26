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
        
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
   
        $request->validate([
            'name' => 'required|unique:categories,name',
            'status' => 'required|in:0,1' 
        ]);

        // Create a new category
        Category::create([
            'name' => $request->name,
            'status' => $request->status, 
        ]);

        // Redirect back with success message
        return redirect()->route('categories.index')->with('success', 'Category added successfully.');
    }

    public function edit(Category $category)
    {
       
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id, 
            'status' => 'required|in:0,1' 
        ]);

        
        $category->update([
            'name' => $request->name,
            'status' => $request->status, 
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
