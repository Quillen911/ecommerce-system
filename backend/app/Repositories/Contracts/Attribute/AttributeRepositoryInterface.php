<?php

namespace App\Repositories\Contracts\Attribute;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface AttributeRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllAttributes();
}
