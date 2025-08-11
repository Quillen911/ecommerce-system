<?php

namespace App\Traits;

use App\Models\Bag;

trait UserBagTrait
{
    public function getUser(){
        return auth()->user();

    }
    public function getUserBag(){
        return Bag::where('bag_user_id', $this->getUser()->id)->first();

    }
}