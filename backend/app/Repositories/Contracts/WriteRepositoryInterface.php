<?php

namespace App\Repositories\Contracts;

interface WriteRepositoryInterface
{
    /**
     * Yeni kayıt oluştur
     */
    public function create(array $data);
    
    /**
     * Kayıt güncelle
     */
    public function update(array $data, $id): bool;
    
    /**
     * Kayıt sil
     */
    public function delete($id): bool;

}