<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AuthValidation\ResetPasswordRequest;
use App\Http\Requests\AuthValidation\ForgotPasswordRequest;
use App\Http\Controllers\Controller;
use App\Services\Auth\PasswordResetService;
use Illuminate\Support\Facades\Response;

class ResetPasswordController extends Controller
{
    public function __construct(
        private readonly PasswordResetService $passwordResetService
    ) {}

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->passwordResetService->sendResetLink($request->validated()['email']);

        return Response::json([
            'message' => 'Parola sıfırlama bağlantısı e‑posta adresinize gönderildi.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $this->passwordResetService->reset($request->validated());

        return Response::json([
            'message' => 'Şifreniz başarıyla güncellendi. Giriş yapabilirsiniz.',
        ]);
    }
}