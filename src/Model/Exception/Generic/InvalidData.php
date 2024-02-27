<?php

namespace App\Model\Exception\Generic;

use DomainException;

class InvalidData extends DomainException
{
    public static function throw(?string $message = "Invalid data")
    {
        throw new self($message);
    }

    public function getStatusCode()
    {
        return 400;
    }
}
