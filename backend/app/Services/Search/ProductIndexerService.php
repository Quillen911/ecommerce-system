<?php

namespace App\Services\Search;

use App\Models\Product;

class ProductIndexerService
{
    public function prepare(Product $product): array
    {
        $data = $product->toArray();
        $data['category_title'] = $product->category?->category_title ?? '';
        $data['total_stock_quantity'] = $product->getTotalStockQuantity();
        $data['gender'] = $product->category?->parent?->category_title ?? '';

        $data['variants'] = $product->variants->map(function ($variant) {
            return [
                'id'             => $variant->id,
                'product_id'     => $variant->product_id,
                'sku'            => $variant->sku,
                'slug'           => $variant->slug,
                'price'          => $variant->price,
                'price_cents'    => $variant->price_cents,
                'stock_quantity' => $variant->stock_quantity,
                'sold_quantity'  => $variant->sold_quantity,
                'is_popular'     => $variant->is_popular,
                'is_active'      => $variant->is_active,
                'images'         => $variant->variantImages->map(fn($image) => [
                    'id'                => $image->id,
                    'product_variant_id'=> $image->product_variant_id,
                    'image'             => asset('storage/productImages/' . $image->image),
                    'is_primary'        => $image->is_primary,
                    'sort_order'        => $image->sort_order
                ])->toArray(),
                'attributes'     => $variant->variantAttributes->map(function ($attr) {
                    return [
                        'attribute_id' => $attr->attribute->id,
                        'code'         => $attr->attribute->code,
                        'name'         => $attr->attribute->name,
                        'value'        => $attr->option->value ?? null,
                        'slug'         => $attr->option->slug,
                    ];
                })->toArray()
            ];
        })->toArray();

        return $data;
    }
}
