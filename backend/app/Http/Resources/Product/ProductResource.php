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
            'title'            => $this->title,
            'category'         => new CategoryResource($this->whenLoaded('category')),
            'description'      => $this->description,
            'meta_title'       => $this->meta_title,
            'meta_description' => $this->meta_description,
            'is_published'     => $this->is_published,
            'variants'         => ProductVariantResource::collection($this->whenLoaded('variants')),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}