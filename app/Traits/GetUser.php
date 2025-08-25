<?php

namespace App\Traits;

use App\Repositories\Contracts\AuthenticationRepositoryInterface;

trait GetUser
{
    protected $authenticationRepository;
    public function __construct(AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->authenticationRepository = $authenticationRepository;
    }
    public function getUser()
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            throw new \Exception('Kullanıcı bulunamadı.');
        }
        return $user;
    }
}