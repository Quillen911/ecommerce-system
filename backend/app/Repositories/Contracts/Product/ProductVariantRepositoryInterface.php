<?php

namespace App\Repositories\Contracts\Product;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductVariantRepositoryInterface extends BaseRepositoryInterface
{
    public function getProductVariantById($productVariantId);
}
