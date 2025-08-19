<?php

namespace App\Providers;

use App\Services\Bag\Contracts\BagInterface;
use App\Services\Bag\Contracts\BagCalculationInterface;
use App\Services\Bag\Contracts\StockInterface;
use App\Services\Bag\Services\BagService;
use App\Services\Bag\Services\BagCalculationService;
use App\Services\Bag\Services\StockService;
use Illuminate\Support\ServiceProvider;

class BagServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BagInterface::class, BagService::class);
        $this->app->bind(BagCalculationInterface::class, BagCalculationService::class);
        $this->app->bind(StockInterface::class, StockService::class);
    }
}