<?php

namespace App\Repositories\Contracts\RefundOrder;

use App\Models\RefundOrderItem;
use App\Repositories\Contracts\BaseRepositoryInterface;

interface RefundOrderItemRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $data);
}