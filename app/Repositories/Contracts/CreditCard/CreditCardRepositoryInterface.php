<?php

namespace App\Repositories\Contracts\CreditCard;

interface CreditCardRepositoryInterface
{
    public function getCreditCardById($id);
    public function getCreditCardsByUserId($userId);
    public function createCreditCard(array $data, $userId);
    public function getCreditCardByUserId($userId, $id);
    public function updateCreditCard(array $data, $id, $userId);
    public function deleteCreditCard($userId, $id);
}
