<?php

namespace App\Repositories\Contracts\Image;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductImageRepositoryInterface extends BaseRepositoryInterface
{
    public function store(array $data, $productId);
    public function getImageByProductIdAndId($productId, $id);
    public function updateImageOrders(int $productId, array $data);
}
