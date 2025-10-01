<?php

namespace App\Http\Resources\ElasticSearch;

use Illuminate\Http\Resources\Json\JsonResource;

class ElasticProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'] ?? null,
            'store_id' => $this['store_id'] ?? null,
            'title' => $this['title'] ?? null,
            'category_id' => $this['category_id'] ?? null,
            'description' => $this['description'] ?? null,
            'meta_title' => $this['meta_title'] ?? null,
            'meta_description' => $this['meta_description'] ?? null,
            'total_sold_quantity' => $this['total_sold_quantity'] ?? null,
            'is_published' => $this['is_published'] ?? null,
            'variants' => collect($this['variants'] ?? [])->map(function ($variant) {
                return [
                    'id' => $variant['id'] ?? null,
                    'sku' => $variant['sku'] ?? null,
                    'slug' => $variant['slug'] ?? null,
                    'price' => $variant['price'] ?? null,
                    'price_cents' => $variant['price_cents'] ?? null,
                    'stock_quantity' => $variant['stock_quantity'] ?? null,
                    'sold_quantity' => $variant['sold_quantity'] ?? null,
                    'is_popular' => $variant['is_popular'] ?? null,
                    'is_active' => $variant['is_active'] ?? null,
                    'images' => collect($variant['images'] ?? [])->map(function ($image) {
                        return [
                            'id' => $image['id'] ?? null,
                            'product_variant_id' => $image['product_variant_id'] ?? null,
                            'image' => $image['image'] ?? null,
                            'is_primary' => $image['is_primary'] ?? null,
                            'sort_order' => $image['sort_order'] ?? null,
                        ];
                    })->toArray(),
                    'attributes' => collect($variant['attributes'] ?? [])->map(function ($attribute) {
                        return [
                            'attribute_id' => $attribute['attribute_id'] ?? null,
                            'code' => $attribute['code'] ?? null,
                            'name' => $attribute['name'] ?? null,
                            'value' => $attribute['value'] ?? null,
                            'slug' => $attribute['slug'] ?? null,
                        ];
                    })->toArray(),
                ];
            })->toArray(),
            'created_at' => isset($this['created_at']) ? \Carbon\Carbon::parse($this['created_at'])->toIso8601String() : null,
            'updated_at' => isset($this['updated_at']) ? \Carbon\Carbon::parse($this['updated_at'])->toIso8601String() : null,

            'category_title' => $this['category_title'] ?? null,
            'gender' => $this['gender'] ?? null,
            'total_stock_quantity' => $this['total_stock_quantity'] ?? null,
        ];
    }
}