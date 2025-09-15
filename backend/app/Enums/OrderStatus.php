<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case PARTIAL_REFUND = 'Kısmi İade';
    case FULL_REFUND = 'İade Edildi';
    case CANCELLED = 'cancelled';
    case FAILED = 'Başarısız Ödeme';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Beklemede',
            self::CONFIRMED => 'Onaylandı',
            self::SHIPPED => 'Kargoda',
            self::DELIVERED => 'Teslim Edildi',
            self::PARTIAL_REFUND => 'Kısmi İade',
            self::FULL_REFUND => 'İade Edildi',
            self::CANCELLED => 'İptal Edildi',
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
            self::PARTIAL_REFUND => 'warning',
            self::FULL_REFUND => 'danger',
            self::CANCELLED => 'danger',
            self::FAILED => 'danger',
        };
    }
}