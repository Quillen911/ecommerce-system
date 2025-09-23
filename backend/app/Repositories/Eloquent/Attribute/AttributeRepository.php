<?php

namespace App\Repositories\Eloquent\Attribute;

use App\Models\Attribute;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Attribute\AttributeRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    public function __construct(Attribute $model)
    {
        $this->model = $model;
    }

    public function getAllAttributes()
    {
        return Cache::remember('attributes.all', 3600, function () {
            return $this->model->all();
        });
    }
}
