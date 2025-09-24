<?php

namespace App\Repositories\Eloquent\Image;

use App\Models\ProductImage;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Image\ProductImageRepositoryInterface;

class ProductImageRepository extends BaseRepository implements ProductImageRepositoryInterface
{
    public function __construct(ProductImage $model)
    {
        $this->model = $model;
    }

    public function store(array $data, $productId)
    {
        $data['product_id'] = $productId;
        return $this->create($data);
    }
}