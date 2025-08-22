<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthValidation\LoginRequest;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Models\Seller;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
class AuthController extends Controller
{
    protected $authenticationRepository;
    public function __construct(AuthenticationRepositoryInterface $authenticationRepository){
        $this->authenticationRepository = $authenticationRepository;
    }
    public function login(LoginRequest $request){
        
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->input('password'), $user->password)){
            return ResponseHelper::error('Email veya Şifre Hatalı',401);
        }
        $token = $user->createToken('user-token')->plainTextToken;
        

        return ResponseHelper::success('Giriş Başarılı', ['token' => $token, 'user' =>$user]);
    }
    
    public function me(Request $request){
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        return ResponseHelper::success('Kullanıcı Bilgileri', $user);
    }

    public function logout(Request $request){
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        $request->user()->currentAccessToken()->delete();

        return ResponseHelper::success('Çıkış Yapıldı.');
    }

    //seller login

    public function sellerLogin(LoginRequest $request){
        $seller = Seller::where('email', $request->email)->first();
        if(!$seller || !Hash::check($request->input('password'), $seller->password)){
            return ResponseHelper::error('Email veya Şifre Hatalı',401);
        }
        $token = $seller->createToken('seller-token')->plainTextToken;
        return ResponseHelper::success('Giriş Başarılı', ['token' => $token, 'seller' =>$seller]);
    }

    public function sellerLogout(Request $request){
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            return ResponseHelper::notFound('Seller bulunamadı.');
        }
        
        $seller->currentAccessToken()->delete();
        
        return ResponseHelper::success('Çıkış Yapıldı.',['seller' => $seller]);
    }

    public function mySeller(Request $request){
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            return ResponseHelper::notFound('Seller bulunamadı.');
        }
        return ResponseHelper::success('Seller Bilgileri', $seller);
    }
}