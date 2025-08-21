<?php

namespace App\Repositories\Contracts\CreditCard;

interface CreditCardRepositoryInterface
{
    public function getCreditCardById($id);
    public function getCreditCardsByUserId($userId);
    public function createCreditCard(array $data);
    public function getCreditCardByUserId($userId, $id);
    public function updateCreditCard(array $data, $id);
    public function deleteCreditCard($userId, $id);
}
