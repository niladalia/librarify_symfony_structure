<?php

namespace App\Model\Exception\Author;

use DomainException;

class AuthorNotFound extends DomainException
{
    public static function throw(?string $id = '')
    {
        throw new self("Author {$id} not found");
    }
    public function getStatusCode()
    {
        return 400;
    }
}
