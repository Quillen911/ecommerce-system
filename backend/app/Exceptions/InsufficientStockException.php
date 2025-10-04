<?php

namespace App\Exceptions;

class InsufficientStockException extends \Exception
{
    protected $variant;
    protected $requestedQuantity;
    protected $availableQuantity;

    public function __construct(String $message, $variant = null, $requested = 0, $available = 0)
    {
        parent::__construct($message);
        $this->variant = $variant;
        $this->requestedQuantity = $requested;
        $this->availableQuantity = $available;
    }

}