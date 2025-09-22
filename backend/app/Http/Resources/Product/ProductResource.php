<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\CategoryResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'store_id'         => $this->store_id,
            'store_name'       => $this->store_name,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'category'         => new CategoryResource($this->whenLoaded('category')),
            'description'      => $this->description,
            'meta_title'       => $this->meta_title,
            'meta_description' => $this->meta_description,
            'list_price'       => $this->list_price,
            'list_price_cents' => $this->list_price_cents,
            'stock_quantity'   => $this->stock_quantity,
            'sold_quantity'    => $this->sold_quantity,
            'is_published'     => $this->is_published,
            'images'           => $this->images ?? [],
            'variants'         => ProductVariantResource::collection($this->whenLoaded('variants')),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}