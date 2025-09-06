<?php

namespace App\Repositories\Eloquent\CreditCard;

use App\Models\CreditCard; 
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\CreditCard\CreditCardRepositoryInterface;

class CreditCardRepository extends BaseRepository implements CreditCardRepositoryInterface
{
    public function __construct(CreditCard $model)
    {
        $this->model = $model;
    }
    public function getCreditCardById($id)
    {
        return $this->model->find($id);
    }

    public function getCreditCardsByUserId($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('id')->get();
    }
    public function createCreditCard(array $data, $userId)
    {
        $data['user_id'] = $userId;
        return $this->create($data);
    }
    public function getCreditCardByUserId($userId, $id)
    {
        return $this->model->where('user_id', $userId)->find($id);
    }
    public function updateCreditCard(array $data, $id, $userId)
    {
        return $this->model->where('id', $id)
                        ->where('user_id', $userId)
                        ->update($data);
    }
    public function deleteCreditCard($userId, $id)
    {
        return $this->model->where('user_id', $userId)->where('id', $id)->delete();
    }
}