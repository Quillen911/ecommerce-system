<?php

namespace App\Services\Auth;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class ProfileService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Kullanıcı profil bilgilerini getir
     */
    public function getUserProfile(int $userId): array
    {
        try {
            $user = $this->userRepository->getUserById($userId);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Kullanıcı bulunamadı.',
                    'errors' => ['error' => 'Kullanıcı bulunamadı.']
                ];
            }

            return [
                'success' => true,
                'message' => 'Profil bilgileri getirildi',
                'data' => [
                    'user' => $user
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Profil bilgileri getirilirken bir hata oluştu: ' . $e->getMessage(),
                'errors' => ['error' => $e->getMessage()]
            ];
        }
    }

    /**
     * Kullanıcı profil bilgilerini güncelle
     */
    public function updateUserProfile(int $userId, array $data): array
    {
        try {
            DB::beginTransaction();

            // Sadece gelen alanları güncelle
            $updateData = array_filter($data, function($value) {
                return $value !== null && $value !== '';
            });

            if (empty($updateData)) {
                return [
                    'success' => false,
                    'message' => 'Güncellenecek veri bulunamadı.',
                    'errors' => ['error' => 'Güncellenecek veri bulunamadı.']
                ];
            }

            $success = $this->userRepository->updateProfile($userId, $updateData);

            if (!$success) {
                return [
                    'success' => false,
                    'message' => 'Profil güncellenirken bir hata oluştu.',
                    'errors' => ['error' => 'Profil güncellenirken bir hata oluştu.']
                ];
            }

            $updatedUser = $this->userRepository->getUserById($userId);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Profil başarıyla güncellendi',
                'data' => [
                    'user' => $updatedUser
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Profil güncellenirken bir hata oluştu: ' . $e->getMessage(),
                'errors' => ['error' => $e->getMessage()]
            ];
        }
    }

    /**
     * Profil tamamlanma oranını hesapla
     */
    public function getProfileCompletionRate(int $userId): array
    {
        try {
            $user = $this->userRepository->getUserById($userId);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Kullanıcı bulunamadı.',
                    'errors' => ['error' => 'Kullanıcı bulunamadı.']
                ];
            }

            $requiredFields = ['username', 'email'];
            $optionalFields = ['phone', 'address', 'city', 'district', 'postal_code'];
            
            $totalFields = count($requiredFields) + count($optionalFields);
            $filledFields = 0;

            // Zorunlu alanlar her zaman dol
            $filledFields += count($requiredFields);

            
            foreach ($optionalFields as $field) {
                if (!empty($user->$field)) {
                    $filledFields++;
                }
            }

            $completionRate = round(($filledFields / $totalFields) * 100, 2);

            return [
                'success' => true,
                'message' => 'Profil tamamlanma oranı hesaplandı',
                'data' => [
                    'completion_rate' => $completionRate,
                    'filled_fields' => $filledFields,
                    'total_fields' => $totalFields,
                    'missing_fields' => $this->getMissingFields($user, $optionalFields)
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Profil tamamlanma oranı hesaplanırken bir hata oluştu: ' . $e->getMessage(),
                'errors' => ['error' => $e->getMessage()]
            ];
        }
    }

    /**
     * Eksik alanları getir
     */
    private function getMissingFields(User $user, array $optionalFields): array
    {
        $missingFields = [];
        
        foreach ($optionalFields as $field) {
            if (empty($user->$field)) {
                $missingFields[] = $field;
            }
        }

        return $missingFields;
    }
}
