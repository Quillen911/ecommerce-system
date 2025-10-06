<?php

namespace App\Repositories\Contracts\Payment;

use App\Models\PaymentProvider;

use App\Repositories\Contracts\BaseRepositoryInterface;
interface PaymentProviderRepositoryInterface extends BaseRepositoryInterface
{
    public function findActiveByCode(string $code): ?PaymentProvider;
}