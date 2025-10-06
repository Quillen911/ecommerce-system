<?php

namespace App\Repositories\Eloquent\Payment;

use App\Models\PaymentProvider;
use App\Repositories\Contracts\Payment\PaymentProviderRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class PaymentProviderRepository extends BaseRepository implements PaymentProviderRepositoryInterface
{
    public function __construct(PaymentProvider $model)
    {
        $this->model = $model;
    }

    public function findActiveByCode(string $code): ?PaymentProvider
    {
        return $this->model->where('code', $code)->where('is_active', true)->first();
    }
}