<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function show($id);
    public function paginate($perPage = 15);
}