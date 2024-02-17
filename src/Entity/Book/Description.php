<?php

namespace App\Entity\Book;

use App\Entity\Book;
use App\Entity\ValueObject\StringValueObject;
use App\Model\Exception\Generic\InvalidArgument;
use InvalidArgumentException;

final class Description extends StringValueObject
{
    /*    public function __construct(?string $value = null)
        {
            parent::__construct($value);
            $this->validate();
        }
    **/
    protected function validate()
    {
        if ($this->value == null) {
            return null;
        }
        if (strlen($this->value) <= 2) {
            InvalidArgument::throw('La descripción tiene que tener un mínimo de 3 caracteres');
        }
    }

    public function update(Book $book): void
    {
        $book->setDescription($this->value);
    }
}
