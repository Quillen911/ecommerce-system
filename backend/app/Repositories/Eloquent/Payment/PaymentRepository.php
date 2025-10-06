<?php

namespace App\Repositories\Eloquent\Payment;

use App\Models\Payment;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Payment\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface 
{
    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes): Payment
    {
        return $this->model->newQuery()->create($attributes);
    }
}
