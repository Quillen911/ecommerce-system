<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PAID = 'paid';
    case PARTIAL_REFUNDED = 'partial_refunded';
    case REFUNDED = 'refunded';
    case CANCELED = 'canceled';
    case FAILED = 'failed';

    public function label(): string
    {
        return match($this) {
            self::PAID => 'Ödendi',
            self::PARTIAL_REFUNDED => 'Kısmi İade',
            self::REFUNDED => 'İade Edildi',
            self::CANCELED => 'İptal Edildi',
            self::FAILED => 'Başarısız',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::PAID => 'success',
            self::PARTIAL_REFUNDED => 'warning',
            self::REFUNDED => 'danger',
            self::CANCELED => 'danger',
            self::FAILED => 'danger',
        };
    }
}