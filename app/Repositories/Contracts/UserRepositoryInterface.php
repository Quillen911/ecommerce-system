<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Yeni kullanıcı oluştur
     */
    public function createUser(array $data): User;

    /**
     * ID'ye göre kullanıcı getir
     */
    public function getUserById(int $id): ?User;

    /**
     * Email'e göre kullanıcı getir
     */
    public function getUserByEmail(string $email): ?User;

    /**
     * Username'e göre kullanıcı getir
     */
    public function getUserByUsername(string $username): ?User;

    /**
     * Kullanıcı profilini güncelle
     */
    public function updateProfile(int $id, array $data): bool;

    /**
     * Kullanıcıyı sil
     */
    public function deleteUser(int $id): bool;
}
