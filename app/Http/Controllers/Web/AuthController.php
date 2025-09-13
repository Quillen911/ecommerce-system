<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthValidation\LoginRequest;
use App\Http\Requests\AuthValidation\RegisterRequest;
use App\Http\Requests\AuthValidation\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
     * Login sayfasını göster
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Register sayfasını göster
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Kullanıcı kayıt işlemi
     */
    public function postRegister(RegisterRequest $request)
    {
        $result = $this->userRegistrationService->registerUser($request->validated());
        
        if ($result['success']) {
            $user = User::where('email', $request->email)->first();
            Auth::guard('user_web')->login($user);
            return redirect()->route('main')->with('success', 'Kayıt başarılı! Hoş geldiniz.');
        }
        
        return back()->withErrors($result['errors'] ?? ['error' => 'Kayıt sırasında bir hata oluştu.']);
    }

    /**
     * Kullanıcı giriş işlemi
     */
    public function postlogin(LoginRequest $request)
    {
        $info = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        if(\Auth::guard('user_web')->attempt($info)) {
            $user = \Auth::guard('user_web')->user();
            return redirect()->route('main');
        }
        return back()->withErrors(['error' => 'Email veya şifre yanlış']);
    }
    /**
     * Kullanıcı çıkış işlemi
     */
    public function logout()
    {
        \Auth::guard('user_web')->logout();
        return redirect()->route('login');
    }

    /**
     * Profil sayfasını göster
     */
    public function profile()
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return redirect()->route('login');
        }
        return view('user.profile', compact('user'));
    }

    /**
     * Profil güncelleme işlemi
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return redirect()->route('login');
        }
        
        $result = $this->profileService->updateUserProfile($user->id, $request->validated());
        
        if ($result['success']) {
            return redirect()->route('profile')->with('success', 'Profil başarıyla güncellendi.');
        }
        
        return back()->withErrors(['error' => 'Profil güncellenirken bir hata oluştu.']);
    }

    public function sellerLogin()
    {
        return view('Seller.sellerlogin');
    }

    public function sellerPostlogin(LoginRequest $request)
    {
        $info = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        if(\Auth::guard('seller_web')->attempt($info)) {
            $seller = \Auth::guard('seller_web')->user();
            return redirect()->route('seller');
        }
        return view('Seller.sellerlogin', ['error' => 'Email veya şifre yanlış']);
        
    }

    public function sellerLogout()
    {
        \Auth::guard('seller_web')->logout();
        return redirect()->route('seller.login');
    }

}