<?php

namespace App\Service\Book;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Model\Exception\Author\AuthorNotFound;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Service\Author\GetAuthor;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateBookAuthor
{
    private $authorRepository;
    private $getAuthor;
    public function __construct(AuthorRepository $authorRepository, GetAuthor $getAuthor)
    {
        $this->authorRepository = $authorRepository;
        $this->getAuthor = $getAuthor;
    }


    public function __invoke(string $newAuthorId, ?Book $book = null): ?Author
    {
        $existingAuthorId = null;

        if ($book) {
            $existingAuthor = $book->getAuthor();
            $existingAuthorId = $existingAuthor ? $existingAuthor->getId() : null;
        }

        if ($newAuthorId != $existingAuthorId) {
            // If the client hasn't selected any author, unlink it from the book; otherwise, assign it
            if (!$newAuthorId) {
                $existingAuthor->removeBook($book);
            } else {
                return ($this->getAuthor)($newAuthorId);
            }
        }
        return null;
    }
}
