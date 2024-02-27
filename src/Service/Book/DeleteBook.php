<?php

namespace App\Service\Book;

use App\Repository\BookRepository;
use App\Model\Exception\Book\BookNotFound;
use App\Service\DeleteFile;

class DeleteBook
{
    public function __construct(
        private BookRepository $bookRep,
        private DeleteFile $file_deleter,
        private BookFinder $bookFinder
    ) {
        $this->bookRep = $bookRep;
        $this->file_deleter = $file_deleter;
        $this->bookFinder = $bookFinder;
    }


    public function __invoke(string $id): void
    {
        $book = ($this->bookFinder)($id);
        if (!$book) {
            BookNotFound::throw();
        }
        if ($book->getImage() != null) {
            ($this->file_deleter)($book->getImage());
        }
        $this->bookRep->delete($book);
    }
}
