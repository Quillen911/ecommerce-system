<?php

namespace App\Repositories\Contracts\Category;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllCategories();
   /* public function getCategoryWithProducts($id);
    public function getActiveCategories();*/
}