<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\Category\CategoryResource;

class CategoryController extends Controller
{
    public function children($id)
    {
        $category = Category::with('children')->findOrFail($id);
        return CategoryResource::collection($category->children);
    }
}
