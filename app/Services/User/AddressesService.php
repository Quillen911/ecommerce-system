<?php

namespace App\Services\User;

use App\Repositories\Contracts\User\AddressesRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Traits\GetUser;

class AddressesService
{
    protected $addressesRepository;
    protected $authenticationRepository;
    use GetUser;
    
    public function __construct(AddressesRepositoryInterface $addressesRepository, AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->addressesRepository = $addressesRepository;
        $this->authenticationRepository = $authenticationRepository;
    }

    public function indexAddresses()
    {
        $userId = $this->getUser();
        return $this->addressesRepository->getAddressesByUserId($userId->id);
    }

    public function storeAddresses(array $data)
    {
        $userId = $this->getUser();
        return $this->addressesRepository->createAddress($data, $userId->id);
    }

    public function showAddresses($id)
    {
        $userId = $this->getUser();
        return $this->addressesRepository->getAddressById($id, $userId->id);
    }

    public function updateAddresses(array $data, $id)
    {
        $userId = $this->getUser();
        return $this->addressesRepository->updateAddress($data, $id, $userId->id);
    }
    public function destroyAddresses($id)
    {
        $userId = $this->getUser();
        return $this->addressesRepository->deleteAddress($id, $userId->id);
    }
}


