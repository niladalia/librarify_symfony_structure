<?php

namespace App\Model\Exception\Generic;

use DomainException;

class InvalidArgument extends DomainException
{
    public static function throw(?string $message = "Invalid arguments")
    {
        throw new self($message);
    }

    public function getStatusCode()
    {
        return 400;
    }
}
