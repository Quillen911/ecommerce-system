<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthValidation\LoginRequest;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->input('password'), $user->password)){
            return response()->json(['message' => 'Email veya Şifre Hatalı'],401);
        }
        $token = $user->createToken('apitoken')->plainTextToken;

        return response()->json(['message' => 'Giriş Başarılı', 'token' => $token, 'user' =>$user], 200);
    }
    
    public function me(Request $request){
        $user = auth()->user();
        if(!$user){
            return response()->json(['message' => 'Kullanıcı bulunamadı.'], 404);
        }
        return response()->json(['message' => 'Kullanıcı Bilgileri', 'user' => $request->user()], 200);
    }

    public function logout(Request $request){
        $user = auth()->user();
        if(!$user){
            return response()->json(['message' => 'Kullanıcı bulunamadı.'], 404);
        }
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Çıkış Yapıldı.'], 200);
    }

}