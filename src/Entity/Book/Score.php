<?php

namespace App\Entity\Book;

use App\Entity\ValueObject\IntValueObject;
use App\Entity\Book;
use App\Model\Exception\Generic\InvalidArgument;
use InvalidArgumentException;

final class Score extends IntValueObject
{
    
    protected function validate() {
        if ($this->value === null) {
            return null;
        }
        if ($this->value > 5 || $this->value < 0) {
            InvalidArgument::throw('El score tiene que estar entre 0 y 5');
        }
    }

}