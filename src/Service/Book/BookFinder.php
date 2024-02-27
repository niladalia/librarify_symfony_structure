<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;
use Ramsey\Uuid\Uuid;

class BookFinder
{
    private $book_rep;

    public function __construct(BookRepository $book_rep)
    {
        $this->book_rep = $book_rep;
    }


    public function __invoke(string $id): ?Book
    {
        $book = $this->book_rep->find(Uuid::fromString($id));
        if (!$book) {
            BookNotFound::throw($id);
        }
        return $book;
    }
}
