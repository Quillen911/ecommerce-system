<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    // READ METHODS
    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function show($id)
    {
        return $this->model->find($id);
    }

    public function findBy(array $criteria): Collection
    {
        $query = $this->model->query();
    
        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }
    
        return $query->get();
    }

    public function paginate($perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function first()
    {
        return $this->model->first();
    }

    public function last()
    {
        return $this->model->orderBy('id', 'desc')->first();
    }

    // WRITE METHODS
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id): bool
    {
        $model = $this->find($id);
        if ($model) {
            return $model->update($data);
        }
        return false;
    }

    public function delete($id): bool
    {
        return $this->model->destroy($id);
    }
}