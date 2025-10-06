<?php

namespace App\Repositories\Eloquent\Payment;

use App\Models\PaymentMethod;
use App\Repositories\Contracts\Payment\PaymentMethodRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class PaymentMethodRepository extends BaseRepository implements PaymentMethodRepositoryInterface
{
    public function __construct(PaymentMethod $model)
    {
        $this->model = $model;
    }

    public function getPaymentMethodForUser($userId, $paymentMethodId): ?PaymentMethod
    {
        return $this->model
            ->where('id', $paymentMethodId)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->first();
    }
}