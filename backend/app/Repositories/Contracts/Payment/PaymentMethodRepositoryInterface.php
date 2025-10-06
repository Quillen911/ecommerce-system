<?php

namespace App\Repositories\Contracts\Payment;

use App\Repositories\Contracts\BaseRepositoryInterface;

use App\Models\PaymentMethod;

interface PaymentMethodRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaymentMethodForUser($userId, $paymentMethodId): ?PaymentMethod;
}