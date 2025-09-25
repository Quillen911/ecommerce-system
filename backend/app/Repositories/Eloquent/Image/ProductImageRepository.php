<?php

namespace App\Repositories\Eloquent\Image;

use App\Models\ProductImage;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Image\ProductImageRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

    public function getImageByProductIdAndId($productId, $id)
    {
        return $this->model->where('product_id', $productId)->where('id', $id)->first();
    }

    public function updateImageOrders(int $productId, array $data)
    {
        DB::transaction(function () use ($productId, $data) {

            $this->model->where('product_id', $productId)->update(['is_primary' => false]);

            foreach ($data as $d) {
                $this->model->where('product_id', $productId)
                    ->where('id', $d['id'])
                    ->update([
                        'sort_order' => $d['sort_order'],
                        'is_primary' => $d['sort_order'] === 1
                    ]);
            }
        });

        return $this->model
            ->where('product_id', $productId)
            ->orderBy('sort_order')
            ->get();
    }
}