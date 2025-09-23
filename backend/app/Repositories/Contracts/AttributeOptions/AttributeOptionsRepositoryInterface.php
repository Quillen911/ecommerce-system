<?php

namespace App\Repositories\Contracts\AttributeOptions;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface AttributeOptionsRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllAttributeOptions();
}