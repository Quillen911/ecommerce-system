<?php

namespace App\Services\Order\Exceptions;

class OrderCreationException extends \Exception
{
    protected $errorCode;

    public function __construct(string $message, ?string $errorCode = null)
    {
        parent::__construct($message);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
}

