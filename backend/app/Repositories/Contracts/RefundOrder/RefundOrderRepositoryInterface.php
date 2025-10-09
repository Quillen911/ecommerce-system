<?php

namespace App\Repositories\Contracts\RefundOrder;

use App\Models\RefundOrder;
use App\Repositories\Contracts\BaseRepositoryInterface;

interface RefundOrderRepositoryInterface extends BaseRepositoryInterface
{
    public function create($data);
}