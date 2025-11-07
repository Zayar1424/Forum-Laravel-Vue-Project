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
}
