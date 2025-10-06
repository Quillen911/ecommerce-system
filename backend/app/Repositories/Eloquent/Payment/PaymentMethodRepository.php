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

    public function createPaymentMethod(array $stored)
    {
        return $this->model->create($stored);
    }

    public function findByProviderToken($provider, $token)
    {
        return $this->model
            ->where('provider', $provider)
            ->where('provider_payment_method_id', $token)
            ->first();
    }

    public function saveFromGateway(array $attributes)
    {
        return tap(
            $this->model->updateOrCreate(
                [
                    'provider'                 => $attributes['provider'],
                    'provider_payment_method_id' => $attributes['provider_payment_method_id'],
                ],
                $attributes
            )
        );
    }
    
}