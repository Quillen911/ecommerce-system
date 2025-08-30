<?php

namespace App\Services\Auth;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class UserRegistrationService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Yeni kullanıcı kaydı oluştur
     */
    public function registerUser(array $data): array
    {
        try {
            DB::beginTransaction();

            // Kullanıcıyı oluştur
            $user = $this->userRepository->createUser($data);

            // Sanctum token oluştur
            $token = $user->createToken('user-token')->plainTextToken;

            DB::commit();

            return [
                'success' => true,
                'message' => 'Kullanıcı başarıyla kaydedildi',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Kullanıcı kaydı sırasında bir hata oluştu: ' . $e->getMessage(),
                'errors' => ['error' => $e->getMessage()]
            ];
        }
    }

}
