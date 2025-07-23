<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthValidation\LoginRequest;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;
use App\Helpers\ResponseHelper;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->input('password'), $user->password)){
            return ResponseHelper::error('Email veya Şifre Hatalı',401);
        }
        $token = $user->createToken('apitoken')->plainTextToken;

        return ResponseHelper::success('Giriş Başarılı', ['token' => $token, 'user' =>$user]);
    }
    
    public function me(Request $request){
        $user = auth()->user();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        return ResponseHelper::success('Kullanıcı Bilgileri', $user);
    }

    public function logout(Request $request){
        $user = auth()->user();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        $request->user()->currentAccessToken()->delete();

        return ResponseHelper::success('Çıkış Yapıldı.');
    }

}