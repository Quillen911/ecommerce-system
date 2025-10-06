<?php

namespace App\Services\Payments;

use App\Models\PaymentProvider;
use App\Services\Payments\Contracts\PaymentGatewayInterface;
use App\Services\Payments\Providers\IyzicoGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, function ($app, array $params) {
            $provider = $params['provider'];
            if (! $provider instanceof PaymentProvider) {
                throw new \InvalidArgumentException('Geçersiz ödeme sağlayıcısı girdiniz.');
            }

            return match ($provider->code) {
                'iyzico' => new IyzicoGateway($provider),
                default  => throw new \RuntimeException("Gateway {$provider->code} desteklenmiyor."),
            };
        });
    }
}
