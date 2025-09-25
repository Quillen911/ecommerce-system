<?php

namespace App\Repositories\Eloquent\Image;

use App\Models\ProductVariantImage;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Image\ProductVariantImageRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductVariantImageRepository extends BaseRepository implements ProductVariantImageRepositoryInterface
{
    public function __construct(ProductVariantImage $model)
    {
        $this->model = $model;
    }

    public function store(array $data, $productVariantId)
    {
        $data['product_variant_id'] = $productVariantId;
        return $this->create($data);
    }

    public function getImageByProductVariantIdAndId($productVariantId, $id)
    {
        return $this->model->where('product_variant_id', $productVariantId)->where('id', $id)->first();
    }

    public function updateImageOrders(int $productVariantId, array $data)
    {
        DB::transaction(function () use ($productVariantId, $data) {
            $this->model->where('product_variant_id', $productVariantId)->update(['is_primary' => false]);
            foreach ($data as $d) {
                $this->model->where('product_variant_id', $productVariantId)
                ->where('id', $d['id'])
                ->update([
                    'sort_order' => $d['sort_order'], 
                    'is_primary' => $d['sort_order'] === 1
                ]);
            }
        });
        
        return $this->model
            ->where('product_variant_id', $productVariantId)
            ->orderBy('sort_order')
            ->get();

    }
}
