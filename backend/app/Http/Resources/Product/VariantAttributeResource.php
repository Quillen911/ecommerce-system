<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantAttributeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'attribute_id' => $this->attribute_id,
            'code'         => $this->attribute->code ?? null,
            'name'         => $this->attribute->name ?? null,
            'value'        => $this->option->value ?? $this->value,
            'slug'         => $this->option->slug ?? null,
        ];
    }
}
