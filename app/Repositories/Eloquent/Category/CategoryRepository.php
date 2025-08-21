<?php

namespace App\Repositories\Eloquent\Category;

use App\Models\Category;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function getAllCategories()
    {
        return Cache::remember('categories.all', 3600, function () {
            return $this->model->where('category_title')->get();
        });
    }
    
    
}