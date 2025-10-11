<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Campaign\CampaignResource;


class MainResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'products'         => ProductResource::collection($this->resource['products']),
            'categories'       => CategoryResource::collection($this->resource['categories']),
            'campaigns'        => CampaignResource::collection($this->resource['campaigns']),
        ];
    }
}
