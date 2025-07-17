<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthValidation\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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
        if(\Auth::attempt($info)) {
            $user = \Auth::user();
            return redirect()->route('main');
        }
        return view('login', ['error' => 'Email veya şifre yanlış']);
        
    }
    public function logout()
    {
        \Auth::logout();
        return redirect()->route('login');

    }
}