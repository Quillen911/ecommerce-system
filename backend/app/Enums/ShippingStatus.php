<?php

namespace App\Enums;

enum ShippingStatus: string
{
    case PENDING = 'pending';
    case PREPARING = 'preparing';
    case SHIPPED = 'shipped';
    case IN_TRANSIT = 'in_transit';
    case DELIVERED = 'delivered';
    case FAILED = 'failed';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Beklemede',
            self::PREPARING => 'Hazırlanıyor',
            self::SHIPPED => 'Kargoya Verildi',
            self::IN_TRANSIT => 'Yolda',
            self::DELIVERED => 'Teslim Edildi',
            self::FAILED => 'Teslim Edilemedi'
        };
    }
}


