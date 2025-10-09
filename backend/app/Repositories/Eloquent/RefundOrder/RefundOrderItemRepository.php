<?php

namespace App\Repositories\Eloquent\RefundOrder;

use App\Models\OrderRefundItem;
use App\Repositories\Contracts\RefundOrder\RefundOrderItemRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class RefundOrderItemRepository extends BaseRepository implements RefundOrderItemRepositoryInterface
{
    public function __construct(OrderRefundItem $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}