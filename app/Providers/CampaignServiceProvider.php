<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Campaigns\CampaignRegistry;
use App\Services\Campaigns\CampaignManager;

class CampaignServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CampaignRegistry::class, function ($app) {
            return new CampaignRegistry();
        });
        
        $this->app->bind(CampaignManager::class, function ($app) {
            return new CampaignManager($app->make(CampaignRegistry::class));
        });
    }
}