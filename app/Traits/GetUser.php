<?php

namespace App\Traits;
   

trait GetUser
{
    protected $authenticationRepository;
    
    public function getUser()
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            throw new \Exception('Kullanıcı bulunamadı.');
        }
        return $user;
    }
}