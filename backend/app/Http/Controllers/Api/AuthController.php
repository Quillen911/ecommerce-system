<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthValidation\LoginRequest;
use App\Http\Requests\AuthValidation\RegisterRequest;
use App\Http\Requests\AuthValidation\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Models\Seller;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Services\Auth\UserRegistrationService;
use App\Services\Auth\ProfileService;
class AuthController extends Controller
{
    protected $authenticationRepository;
    protected $userRegistrationService;
    protected $profileService;

    public function __construct(
        AuthenticationRepositoryInterface $authenticationRepository,
        UserRegistrationService $userRegistrationService,
        ProfileService $profileService
    ){
        $this->authenticationRepository = $authenticationRepository;
        $this->userRegistrationService = $userRegistrationService;
        $this->profileService = $profileService;
    }
    /**
     * Kullanıcı kayıt işlemi
     */
    public function register(RegisterRequest $request){
        return $this->userRegistrationService->registerUser($request->validated());
    }

    /**
     * Kullanıcı giriş işlemi
     */
    public function login(LoginRequest $request){
        
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->input('password'), $user->password)){
            return ResponseHelper::error('Email veya Şifre Hatalı',401);
        }
        $token = $user->createToken('user-token')->plainTextToken;
        
        // HttpOnly cookie ile token'ı güvenli şekilde set et
        cookie()->queue(
            'user_token',
            $token,
            60 * 24, // 24 saat
            '/',
            null,
            config('session.secure', false), // HTTPS'de true
            true, // HttpOnly
            false,
            'strict' // SameSite
        );

        return ResponseHelper::success('Giriş Başarılı', ['token' => $token, 'user' =>$user]);
    }
    
    /**
     * Mevcut kullanıcı bilgilerini getir
     */
    public function me(Request $request){
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        return ResponseHelper::success('Kullanıcı Bilgileri', $user);
    }

    /**
     * Kullanıcı profil bilgilerini getir
     */
    public function profile(Request $request){
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        
        $result = $this->profileService->getUserProfile($user->id);
        
        if ($result['success']) {
            return ResponseHelper::success($result['message'], $result['data']);
        } else {
            return ResponseHelper::error($result['message'], 400, $result['errors'] ?? []);
        }
    }

    /**
     * Kullanıcı profil bilgilerini güncelle
     */
    public function updateProfile(UpdateProfileRequest $request){
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        
        $result = $this->profileService->updateUserProfile($user->id, $request->validated());
        
        if ($result['success']) {
            return ResponseHelper::success($result['message'], $result['data']);
        } else {
            return ResponseHelper::error($result['message'], 400, $result['errors'] ?? []);
        }
    }

    public function logout(Request $request){
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        $request->user()->currentAccessToken()->delete();
        
        // Cookie'yi sil
        cookie()->queue(cookie()->forget('user_token'));

        return ResponseHelper::success('Çıkış Yapıldı.');
    }

    //seller login

    public function sellerLogin(LoginRequest $request){
        $seller = Seller::where('email', $request->email)->first();
        if(!$seller || !Hash::check($request->input('password'), $seller->password)){
            return ResponseHelper::error('Email veya Şifre Hatalı',401);
        }
        $token = $seller->createToken('seller-token')->plainTextToken;
        
        // HttpOnly cookie ile token'ı güvenli şekilde set et
        cookie()->queue(
            'seller_token',
            $token,
            60 * 24, // 24 saat
            '/',
            null,
            config('session.secure', false), // HTTPS'de true
            true, // HttpOnly
            false,
            'strict' // SameSite
        );
        
        return ResponseHelper::success('Giriş Başarılı', ['token' => $token, 'seller' =>$seller]);
    }

    public function sellerLogout(Request $request){
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            return ResponseHelper::notFound('Seller bulunamadı.');
        }
        
        $seller->currentAccessToken()->delete();
        
        // Cookie'yi sil
        cookie()->queue(cookie()->forget('seller_token'));
        
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