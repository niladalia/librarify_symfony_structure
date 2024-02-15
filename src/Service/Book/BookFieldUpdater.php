<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Entity\Book\Description;
use App\Entity\Book\Score;
use App\Entity\Book\Title;
use App\Repository\BookRepository;
use App\Interfaces\FileUploaderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class BookFieldUpdater
{
    private $book_rep;
    private $getBook;
    private $updateBookAuthor;

    public function __construct(
        BookRepository $book_rep,
        GetBook $getBook,
        UpdateBookAuthor $updateBookAuthor
    ) {
        $this->book_rep = $book_rep;
        $this->getBook = $getBook;
        $this->updateBookAuthor = $updateBookAuthor;
    }

    public function __invoke(Request $request, string $id): Book
    {
        $book = ($this->getBook)($id);
        $data = json_decode($request->getContent(),true);
        
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
            $new_author = ($this->updateBookAuthor)($data['author_id'],$book);
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