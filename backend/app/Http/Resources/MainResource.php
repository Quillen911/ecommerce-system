<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Campaign\CampaignResource;
use App\Http\Resources\Product\AttributeResource;
use App\Http\Resources\Product\AttributeOptionResource;

class MainResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'products'         => ProductResource::collection($this->resource['products']),
            'categories'       => CategoryResource::collection($this->resource['categories']),
            'campaigns'        => CampaignResource::collection($this->resource['campaigns']),
            'attributes'       => AttributeResource::collection($this->resource['attributes']),
            'attributeOptions' => AttributeOptionResource::collection($this->resource['attributeOptions']),
        ];
    }
}
