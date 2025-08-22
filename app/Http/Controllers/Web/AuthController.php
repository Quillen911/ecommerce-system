<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthValidation\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
class AuthController extends Controller
{
    protected $authenticationRepository;
    public function __construct(AuthenticationRepositoryInterface $authenticationRepository){
        $this->authenticationRepository = $authenticationRepository;
    }
    public function login()
    {
        return view('login');
    }

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
        return view('login', ['error' => 'Email veya şifre yanlış']);
        
    }
    public function logout()
    {
        \Auth::guard('user_web')->logout();
        return redirect()->route('login');

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