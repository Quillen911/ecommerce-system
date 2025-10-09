<?php

namespace App\Repositories\Eloquent\RefundOrder;

use App\Models\OrderRefund;
use App\Repositories\Contracts\RefundOrder\RefundOrderRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class RefundOrderRepository extends BaseRepository implements RefundOrderRepositoryInterface
{
    public function __construct(OrderRefund $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }
}