php artisan db:seed --class="Database\\Seeders\\Campaigns\\CampaignSeeder"
php artisan db:seed --class="Database\\Seeders\\Campaigns\\CampaignProductSeeder"
php artisan optimize:clear
php artisan route:list | grep auth.api
exit
