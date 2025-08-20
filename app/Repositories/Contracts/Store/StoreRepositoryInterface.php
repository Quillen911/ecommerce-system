<?php

namespace App\Repositories\Contracts\Store;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface StoreRepositoryInterface extends BaseRepositoryInterface
{
    public function getStoreBySellerId($sellerId);
    public function getStoreWithSeller($sellerId);
}
