<?php

namespace App\Repositories\Eloquent\AttributeOptions;

use App\Models\AttributeOption;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\AttributeOptions\AttributeOptionsRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AttributeOptionsRepository extends BaseRepository implements AttributeOptionsRepositoryInterface
{
    public function __construct(AttributeOption $model)
    {
        $this->model = $model;
    }

    public function getAllAttributeOptions()
    {
        return Cache::remember('attribute_options.all', 3600, function () {
            return $this->model->all();
        });
    }
}
