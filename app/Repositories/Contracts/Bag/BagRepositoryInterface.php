<?php

namespace App\Repositories\Contracts\Bag;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface BagRepositoryInterface extends BaseRepositoryInterface    
{
    public function getBag($user);
    public function createBag($user);
}