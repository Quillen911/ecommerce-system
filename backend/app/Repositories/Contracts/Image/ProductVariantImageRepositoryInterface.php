<?php

namespace App\Repositories\Contracts\Image;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductVariantImageRepositoryInterface extends BaseRepositoryInterface
{
    public function store(array $data, $productVariantId);
}
