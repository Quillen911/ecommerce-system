<?php

namespace App\Repositories\Contracts\Order;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Models\Order;
interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getOrdersBySeller($sellerId);
    public function create(array $attributes): Order;
    public function getOrdersForUser(int|string $id, $userId): Order;
    public function latest(): ?Order;
}