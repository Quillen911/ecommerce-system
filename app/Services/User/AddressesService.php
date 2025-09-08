<?php

namespace App\Services\User;

use App\Repositories\Contracts\User\AddressesRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Traits\GetUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $user = $this->getUser();
        $addresses = $this->addressesRepository->getAddressesByUserId($user->id);
        if($addresses->isEmpty()){
            throw new ModelNotFoundException('Adres bulunamadı.');
        }
        return $addresses;
    }

    public function storeAddresses(array $data)
    {
        $user = $this->getUser();
        return $this->addressesRepository->createAddress($data, $user->id);
    }

    public function showAddresses($id)
    {
        $user = $this->getUser();
        $address = $this->addressesRepository->getAddressById($id, $user->id);
        if(!$address){
            throw new ModelNotFoundException('Adres bulunamadı.');
        }
        return $address;
    }

    public function updateAddresses(array $data, $id)
    {
        $user = $this->getUser();
        $address = $this->addressesRepository->updateAddress($data, $id, $user->id);
        if(!$address){
            throw new ModelNotFoundException('Adres bulunamadı.');
        }
        return $address;
    }
    public function destroyAddresses($id)
    {
        $user = $this->getUser();
        $address = $this->addressesRepository->deleteAddress($id, $user->id);
        if(!$address){
            throw new ModelNotFoundException('Adres bulunamadı.');
        }
        return true;
    }
}


