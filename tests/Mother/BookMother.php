<?php

declare(strict_types=1);

namespace App\Tests\Mother;

use App\Entity\Book;
use App\Entity\Book\Title;
use App\Form\Model\BookDto;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class BookMother
{
    public static function create(?UuidInterface $id = null, ?Title $title = null): Book
    {
        return new Book(
            $id ?? Uuid::uuid4(),
            $title ?? new Title(),
            null,
            null
        );
    }
}
