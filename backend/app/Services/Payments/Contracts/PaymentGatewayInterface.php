<?php

namespace App\Services\Payments\Contracts;

use App\Models\CheckoutSession;
use App\Models\PaymentMethod;
use App\Models\User;

interface PaymentGatewayInterface
{
    /**
     * Yeni kart ile işlem
     */
    public function buildTemporaryMethod(User $user, array $data): PaymentMethod;

    /**
     *  – 3D yönlendirme dahil. Ödeme niyeti
     */
    public function processPayment(
        User $user,
        CheckoutSession $session,
        PaymentMethod $method,
        array $data
    ): array;

    /**
     * 3D callback / tamamla adımı
     */
    public function confirmPayment(CheckoutSession $session, array $payload): array;

    /**
     * Kart kaydetme senaryosu
     */
    public function storePaymentMethod(
        User $user, 
        $method, 
        array $payload
    ): PaymentMethod;
}
