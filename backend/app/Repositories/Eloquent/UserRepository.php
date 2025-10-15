<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Yeni kullanıcı oluştur
     */
    public function createUser(array $data): User
    {
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->model->create($data);
    }

    /**
     * ID'ye göre kullanıcı getir
     */
    public function getUserById(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Email'e göre kullanıcı getir
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Username'e göre kullanıcı getir
     */
    public function getUserByUsername(string $username): ?User
    {
        return $this->model->where('username', $username)->first();
    }

    /**
     * Kullanıcı profilini güncelle
     */
    public function updateProfile(int $id, array $data): bool
    {
        $user = $this->getUserById($id);
        
        if (!$user) {
            return false;
        }

        return $user->update($data);
    }

    /**
     * Kullanıcıyı sil
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);
        
        if (!$user) {
            return false;
        }

        return $user->delete();
    }
}
