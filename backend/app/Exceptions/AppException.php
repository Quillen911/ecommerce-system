<?php

namespace App\Exceptions;

class AppException extends \Exception
{
    protected $errorCode;

    public function __construct($message = "", $errorCode = null, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
