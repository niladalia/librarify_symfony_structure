<?php

namespace App\Entity\ValueObject;

use App\Entity\Book;

// La fem abstract perque no volem que sigui instanciable i perque conté u metode abstracte a mes a mes de metodes implementats
interface Field
{
    public function getValue();
    // public function update(Book $book): void;
}
