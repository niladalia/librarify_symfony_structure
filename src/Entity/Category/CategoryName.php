<?php

namespace App\Entity\Category;

use App\Entity\ValueObject\StringValueObject;
use App\Model\Exception\Generic\InvalidArgument;

final class CategoryName extends StringValueObject
{
    protected function validate()
    {
        if ($this->value == null) {
            return null;
        }
        if (strlen($this->value) <= 2) {
            InvalidArgument::throw('El nombre tiene que tener un mínimo de 3 caracteres');
        }
    }
}
