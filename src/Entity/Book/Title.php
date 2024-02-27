<?php

namespace App\Entity\Book;

use App\Entity\Book;
use App\Entity\ValueObject\StringValueObject;
use App\Model\Exception\Generic\InvalidArgument;
use InvalidArgumentException;

final class Title extends StringValueObject
{
    protected function validate()
    {
        if ($this->value == null) {
            InvalidArgument::throw('El titulo no puede estar vacío');
        }
        if (strlen($this->value) <= 2) {
            InvalidArgument::throw('El titulo tiene que tener un mínimo de 3 caracteres');
        }
    }
}
