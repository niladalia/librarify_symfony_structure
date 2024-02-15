<?php

namespace App\Model\Exception\Category;

use DomainException;

class CategoryNotFound extends DomainException
{
    public static function throw(?string $id = '')
    {
        throw new self("Category {$id} not found");
    }
    public function getStatusCode(){
        return 400;
    }
}