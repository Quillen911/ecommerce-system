<?php

namespace App\Repositories\Contracts\Product;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductVariantRepositoryInterface extends BaseRepositoryInterface
{
    public function getProductVariants($productId);
    public function getProductVariantById($variantId);
    public function getProductVariant($productId,$variantId);
    public function getProductVariantBySlug($slug);
    public function getPopularAllVariants();
    public function createVariant($data, $productId);
    public function updateVariant($productId, $id, $data);
    public function deleteVariant($productId, $id);
}
