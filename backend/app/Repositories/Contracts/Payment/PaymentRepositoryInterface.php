<?php

namespace App\Repositories\Contracts\Payment;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Models\Payment;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $attributes): Payment;
}