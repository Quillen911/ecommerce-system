<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReadRepositoryInterface
{
    /**
     * Tüm kayıtları getir
     */
    public function all(): Collection;
    
    /**
     * ID'ye göre tek kayıt getir
     */
    public function find($id);
    
    /**
     * ID'ye göre tek kayıt getir (show method'u ile aynı)
     */
    public function show($id);
    
    /**
     * Kriterlere göre kayıtları getir
     */
    public function findBy(array $criteria): Collection;
    
    /**
     * Sayfalama ile kayıtları getir
     */
    public function paginate($perPage = 15): LengthAwarePaginator;
    
    /**
     * İlk kaydı getir
     */
    public function first();
    
    /**
     * Son kaydı getir
     */
    public function last();
}