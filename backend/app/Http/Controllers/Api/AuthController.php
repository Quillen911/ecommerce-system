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
use Illuminate\Support\Facades\Response;

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
    
        $cookie = cookie(
            'user_token',
            $token,
            config('session.lifetime'), 
            '/',
            null,
            env('SESSION_SECURE_COOKIE', false), 
            true, 
            false,
            env('SESSION_SAME_SITE', 'lax')
        );

        $response = ResponseHelper::success('Giriş Başarılı', ['token' => $token, 'user' =>$user]);

        return $response->withCookie($cookie);
    }
    
    /**
     * Mevcut kullanıcı bilgilerini getir
     */
    public function me(Request $request){
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return Response::json([
                'message' => 'Kullanıcı bulunamadı.'
            ], 404);
        }
        return ResponseHelper::success('Kullanıcı Bilgileri', $user);
    }

    /**
     * Kullanıcı profil bilgilerini getir
     */
    public function profile(Request $request){
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return Response::json([
                'message' => 'Kullanıcı bulunamadı.'
            ], 404);
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
            return Response::json([
                'message' => 'Kullanıcı bulunamadı.'
            ], 404);
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
            return Response::json([
                'message' => 'Kullanıcı bulunamadı.'
            ], 404);
        }
        $cookieToken = $request->cookie('user_token');

        if ($cookieToken) {
            $cookieToken = urldecode($cookieToken);

            if (strpos($cookieToken, '|') !== false) {
                [$id, $plainToken] = explode('|', $cookieToken, 2);
                $accessToken = \Laravel\Sanctum\PersonalAccessToken::find($id);

                if ($accessToken && hash_equals($accessToken->token, hash('sha256', $plainToken))) {
                    $accessToken->delete();
                }
            }
        }
        
        $forgetCookie = cookie()->forget('user_token');

        return ResponseHelper::success('Çıkış Yapıldı.')->withCookie($forgetCookie);
    }

    //seller login

    public function sellerLogin(LoginRequest $request){
        $seller = Seller::where('email', $request->email)->first();
        if(!$seller || !Hash::check($request->input('password'), $seller->password)){
            return ResponseHelper::error('Email veya Şifre Hatalı',401);
        }
        $token = $seller->createToken('seller-token')->plainTextToken;
        
        $cookie = cookie(
            'seller_token',
            $token,
            config('session.lifetime'),
            '/',
            null,
            env('SESSION_SECURE_COOKIE', false), 
            true, 
            false,
            env('SESSION_SAME_SITE', 'lax')
        );

        
        $response = ResponseHelper::success('Giriş Başarılı', ['token' => $token, 'seller' =>$seller]);
        return $response->withCookie($cookie);
    }

    public function sellerLogout(Request $request){
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            return Response::json([
                'message' => 'Satıcı bulunamadı.'
            ], 404);
        }
        
        $cookieToken = $request->cookie('seller_token');

        if ($cookieToken) {
            $cookieToken = urldecode($cookieToken);

            if (strpos($cookieToken, '|') !== false) {
                [$id, $plainToken] = explode('|', $cookieToken, 2);
                $accessToken = \Laravel\Sanctum\PersonalAccessToken::find($id);

                if ($accessToken && hash_equals($accessToken->token, hash('sha256', $plainToken))) {
                    $accessToken->delete();
                }
            }
        }
        $forgetCookie = cookie()->forget('seller_token');
        return ResponseHelper::success('Çıkış Yapıldı.',['seller' => $seller])->withCookie($forgetCookie);
    }

    public function mySeller(Request $request){
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            return Response::json([
                'message' => 'Satıcı bulunamadı.'
            ], 404);
        }
        return ResponseHelper::success('Satıcı Bilgileri', $seller);
    }
}