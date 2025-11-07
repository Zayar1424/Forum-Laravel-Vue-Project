<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::orderByDesc('created_at')->get();
        return inertia('admin/Categories/Index', ['categories' => $categories]);
    }

    public function destroy(Category $category){
        $category->delete();
        return back();
    }

    public function createForm(){
        return inertia('admin/Categories/CategoryForm');
    }

    public function store(){
        $data = request()->validate([
            'name' => 'required|string|unique:categories,name',
            'slug' => 'required|string|unique:categories,slug',
            'description' => 'required|string',
        ]);

        Category::create($data);

        return redirect()->route('admin.categories');
    }

    public function edit(Category $category){
        return inertia('admin/Categories/CategoryForm', ['category' => $category]);
    }

    public function update(Category $category){
        $data = request()->validate([
            'name' => 'required|string|unique:categories,name,'.$category->id,
            'slug' => 'required|string|unique:categories,slug,'.$category->id,
            'description' => 'required|string',
        ]);

        $category->update($data);

        return redirect()->route('admin.categories');
    }
}
