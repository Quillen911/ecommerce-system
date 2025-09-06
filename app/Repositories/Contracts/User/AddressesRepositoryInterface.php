<?php

namespace App\Repositories\Contracts\User;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface AddressesRepositoryInterface extends BaseRepositoryInterface
{
    public function getAddressesByUserId($userId);
    public function getAddressById($id, $userId);
    public function createAddress(array $data, $userId);
    public function updateAddress(array $data, $id, $userId);
    public function deleteAddress($id, $userId);
}
