<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Entity\Book\Description;
use App\Entity\Book\Score;
use App\Entity\Book\Title;
use App\Repository\BookRepository;

class BookFieldUpdater
{
    private $book_rep;
    private $bookFinder;
    private $updateBookAuthor;

    public function __construct(
        BookRepository $book_rep,
        bookFinder $bookFinder,
        UpdateBookAuthor $updateBookAuthor
    ) {
        $this->book_rep = $book_rep;
        $this->bookFinder = $bookFinder;
        $this->updateBookAuthor = $updateBookAuthor;
    }

    public function __invoke(array $data, string $id): Book
    {
        $book = ($this->bookFinder)($id);

        if (\array_key_exists('score', $data)) {
            $book->setScore(new Score($data['score']));
        }
        if (\array_key_exists('title', $data)) {
            $book->setTitle(new Title($data['title']));
        }
        if (\array_key_exists('description', $data)) {
            $book->setDescription(new Description($data['description']));
        }
        if (\array_key_exists('author_id', $data)) {
            $new_author = ($this->updateBookAuthor)($data['author_id'], $book);
            $new_author ? $book->setAuthor($new_author) : null;
        }
        $book = $this->book_rep->save($book);
        return $book;
    }

    /*
    $field = array_keys($data)[0];
    $d='App\Entity\Book\'.upfirst($field);
    $dd = new $d;
    */
}
