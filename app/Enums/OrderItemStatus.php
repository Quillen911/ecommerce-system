<?php

namespace App\Enums;

enum OrderItemStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case REFUNDED = 'refunded';
    case CUSTOMER_RETURNED = 'Müşteri İade Etti';
    case FAILED = 'Başarısız Ödeme';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Beklemede',
            self::CONFIRMED => 'Onaylandı',
            self::SHIPPED => 'Kargoda',
            self::DELIVERED => 'Teslim Edildi',
            self::REFUNDED => 'İade Edildi',
            self::CUSTOMER_RETURNED => 'Müşteri İade Etti',
            self::FAILED => 'Başarısız Ödeme',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'success',
            self::SHIPPED => 'info',
            self::DELIVERED => 'success',
            self::REFUNDED => 'danger',
            self::CUSTOMER_RETURNED => 'danger',
            self::FAILED => 'danger',
        };
    }
}
