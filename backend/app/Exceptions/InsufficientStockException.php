<?php

namespace App\Exceptions;

class InsufficientStockException extends \Exception
{
    protected $product;
    protected $requestedQuantity;
    protected $availableQuantity;

    public function __construct(String $message, $product = null, $requested = 0, $available = 0)
    {
        parent::__construct($message);
        $this->product = $product;
        $this->requestedQuantity = $requested;
        $this->availableQuantity = $available;
    }

}