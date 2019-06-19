<?php


namespace BlackboardLearn\Exception;


use Throwable;

class BadRequestException extends \Exception
{
    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}