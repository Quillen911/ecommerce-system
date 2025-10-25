<?php

namespace App\Services\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Hashing\Hasher;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\PasswordReset as PasswordResetModel;
use App\Notifications\PasswordResetNotification;    
use Illuminate\Validation\ValidationException;
use App\Jobs\ResetPasswordNotificationJob;
use Carbon\Carbon;

class PasswordResetService
{

    public function __construct(private readonly Hasher $hasher) {}

    public function sendResetLink(string $email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Bu e‑posta ile kayıtlı kullanıcı bulunamadı.'],
            ]);
        }

        $plainToken = Str::random(64);
        $hashedToken = hash('sha256', $plainToken);

        PasswordResetModel::create([
                'user_id'    => $user->id,
                'email'      => $user->email,
                'token'      => $hashedToken,
                'status'     => 'sent',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        dispatch(new ResetPasswordNotificationJob($user, $plainToken));


    }
    public function reset(array $data)
    {
        $hashedToken = hash('sha256', $data['token']);
        $reset = PasswordResetModel::where('email', $data['email'])->where('token', $hashedToken)->first();
        if (!$reset) {
            throw ValidationException::withMessages([
                'token' => ['Parola sıfırlama bağlantınız geçersiz veya süresi dolmuş.'],
            ]);
        }
        
        if(Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            $reset->delete();
            throw ValidationException::withMessages([
                'token' => ['Parola sıfırlama bağlantınız geçersiz veya süresi dolmuş.'],
            ]);
        }

        $user = User::where('email', $data['email'])->first();

        $user->forceFill([
            'password' => $this->hasher->make($data['password']),
            'remember_token' => $this->hasher->make(Str::random(60)),
        ])->save();

        $reset->forceFill([
            'status' => 'completed',
            'token' => $this->hasher->make($hashedToken)
        ])->save();
        $reset->delete();

        event(new PasswordReset($user));
    }
}