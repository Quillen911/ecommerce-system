<?php

namespace App\Repositories\Eloquent\Product;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Product\ProductVariantRepositoryInterface;
use App\Models\ProductVariant;

class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{
    public function __construct(ProductVariant $model)
    {
        $this->model = $model;
    }

    public function getProductVariantById($productVariantId)
    {
        return $this->model->where('id', $productVariantId)->first();
    }

}