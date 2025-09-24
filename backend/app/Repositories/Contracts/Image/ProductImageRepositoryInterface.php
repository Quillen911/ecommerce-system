<?php

namespace App\Repositories\Contracts\Image;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductImageRepositoryInterface extends BaseRepositoryInterface
{
    public function store(array $data, $productId);
}
