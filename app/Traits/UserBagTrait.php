<?php

namespace App\Traits;

use App\Models\Bag;

trait UserBagTrait
{
    public function getUser(){
        // API için sanctum, web için web guard kullan
        if (request()->is('api/*') || request()->expectsJson()) {
            return auth('sanctum')->user();
        }
        return auth('web')->user();
    }
    public function getUserBag(){
        return Bag::where('bag_user_id', $this->getUser()->id)->first();

    }
}