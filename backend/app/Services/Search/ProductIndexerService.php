<?php

namespace App\Services\Search;

use App\Models\Product;

class ProductIndexerService
{
    public function prepare(Product $product): array
    {
        $data = $product->toArray();
        if ($product->category) {
            $data['category'] = [
                'id' => $product->category->id,
                'title' => $product->category->title,
                'slug' => $product->category->slug,
                'gender_id' => $product->category->gender_id,
                'parent_id' => $product->category->parent_id,
                'gender' => $product->category->gender ? [
                    'id' => $product->category->gender->id,
                    'title' => $product->category->gender->title,
                    'slug' => $product->category->gender->slug,
                ] : null,
                'parent' => $product->category->parent ? [
                    'id' => $product->category->parent->id,
                    'title' => $product->category->parent->title,
                    'slug' => $product->category->parent->slug,
                ] : null,
            ];
        }

        $data['category_title'] = $product->category?->title ?? '';
        $data['gender'] = $product->category?->gender?->title ?? '';

        $data['variants'] = $product->variants->map(function ($variant) {
            return [
                'id'             => $variant->id,
                'product_id'     => $variant->product_id,
                'sku'            => $variant->sku,
                'slug'           => $variant->slug,
                'price_cents'    => $variant->price_cents,
                'color_name'     => $variant->color_name,
                'color_code'     => $variant->color_code,
                'is_popular'     => $variant->is_popular,
                'is_active'      => $variant->is_active,
                'images'         => $variant->variantImages->map(fn($image) => [
                    'id'                => $image->id,
                    'product_variant_id'=> $image->product_variant_id,
                    'image'             => asset('storage/productImages/' . $image->image),
                    'is_primary'        => $image->is_primary,
                    'sort_order'        => $image->sort_order
                ])->toArray(),
                
                'sizes'     => $variant->variantSizes->map(function ($size) {
                    return [
                        'id'                 => $size->id,
                        'product_variant_id' => $size->product_variant_id,
                        'size_option_id'     => $size->sizeOption->id,
                        'size_option'        => [
                            'id'             => $size->sizeOption->id,
                            'attribute_id'   => $size->sizeOption->attribute_id,
                            'value'          => $size->sizeOption->value,
                            'slug'           => $size->sizeOption->slug,
                        ],
                        'sku'                => $size->sku,
                        'price_cents'        => $size->price_cents,
                        'is_active'          => $size->is_active,
                        'inventory'          => $size->inventory ? [
                            'id'             => $size->inventory->id,
                            'variant_size_id'=> $size->inventory->variant_size_id,
                            'warehouse_id'   => $size->inventory->warehouse_id,
                            'on_hand'        => $size->inventory->on_hand,
                            'reserved'       => $size->inventory->reserved,
                            'available'      => $size->inventory->available,
                            'min_stock_level'=> $size->inventory->min_stock_level
                        ] : null
                    ];
                })->toArray()
            ];
        })->toArray();

        return $data;
    }
}