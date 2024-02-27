<?php

namespace App\Service\Book;

use App\Entity\Book\Books;
use App\Repository\BookRepository;

class BooksFinder
{
    private $book_rep;

    public function __construct(BookRepository $book_rep)
    {
        $this->book_rep = $book_rep;
    }


    public function __invoke(): Books
    {
        $books = $this->book_rep->find_all();

        return $books;
    }
}
