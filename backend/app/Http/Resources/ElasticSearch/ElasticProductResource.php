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
            'slug' => $this['slug'] ?? null,
            'category' => isset($this['category']) ? [
                'id' => $this['category']['id'] ?? null,
                'title' => $this['category']['title'] ?? null,
                'slug' => $this['category']['slug'] ?? null,
                'gender_id' => $this['category']['gender_id'] ?? null,
                'parent_id' => $this['category']['parent_id'] ?? null,
                'gender' => isset($this['category']['gender']) ? [
                    'id' => $this['category']['gender']['id'] ?? null,
                    'title' => $this['category']['gender']['title'] ?? null,
                    'slug' => $this['category']['gender']['slug'] ?? null,
                ] : null,
                'parent' => isset($this['category']['parent']) ? [
                    'id' => $this['category']['parent']['id'] ?? null,
                    'title' => $this['category']['parent']['title'] ?? null,
                    'slug' => $this['category']['parent']['slug'] ?? null,
                ] : null,
            ] : null,
            'description' => $this['description'] ?? null,
            'meta_title' => $this['meta_title'] ?? null,
            'meta_description' => $this['meta_description'] ?? null,
            'is_published' => $this['is_published'] ?? null,
            'variants' => collect($this['variants'] ?? [])->map(function ($variant) {
                return [
                    'id' => $variant['id'] ?? null,
                    'sku' => $variant['sku'] ?? null,
                    'slug' => $variant['slug'] ?? null,
                    'price_cents' => $variant['price_cents'] ?? null,
                    'color_name' => $variant['color_name'] ?? null,
                    'color_code' => $variant['color_code'] ?? null,
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
                    'sizes' => collect($variant['sizes'] ?? [])->map(function ($size) {
                        return [
                            'id' => $size['id'] ?? null,
                            'product_variant_id' => $size['product_variant_id'] ?? null,
                            'size_option_id' => $size['size_option_id'] ?? null,
                            'size_option' => isset($size['size_option']) ? [
                                'id' => $size['size_option']['id'] ?? null,
                                'attribute_id' => $size['size_option']['attribute_id'] ?? null,
                                'value' => $size['size_option']['value'] ?? null,
                                'slug' => $size['size_option']['slug'] ?? null,
                            ] : null,
                            'sku' => $size['sku'] ?? null,
                            'price_cents' => $size['price_cents'] ?? null,
                            'is_active' => $size['is_active'] ?? null,
                            'inventory' => isset($size['inventory']) ? [
                                'id' => $size['inventory']['id'] ?? null,
                                'variant_size_id' => $size['inventory']['variant_size_id'] ?? null,
                                'warehouse_id' => $size['inventory']['warehouse_id'] ?? null,
                                'on_hand' => $size['inventory']['on_hand'] ?? null,
                                'reserved' => $size['inventory']['reserved'] ?? null,
                                'available' => $size['inventory']['available'] ?? null,
                                'min_stock_level' => $size['inventory']['min_stock_level'] ?? null,
                            ] : null,
                        ];
                    })->toArray(),
                ];
            })->toArray(),
            'created_at' => isset($this['created_at']) ? \Carbon\Carbon::parse($this['created_at'])->toIso8601String() : null,
            'updated_at' => isset($this['updated_at']) ? \Carbon\Carbon::parse($this['updated_at'])->toIso8601String() : null,

            'category_title' => $this['category_title'] ?? null,
            'gender' => $this['gender'] ?? null,
        ];
    }
}