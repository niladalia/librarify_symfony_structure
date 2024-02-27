<?php

namespace App\Service\Book;

use App\Entity\Author;
use App\Entity\Book;
use App\Service\Author\GetAuthor;

class UpdateBookAuthor
{
    private $getAuthor;
    public function __construct(GetAuthor $getAuthor)
    {
        $this->getAuthor = $getAuthor;
    }


    public function __invoke(string $newAuthorId, ?Book $book = null): ?Author
    {
        $existingAuthorId = null;
        // Si ens envien el Book, busquem l'autor que tenia asignat i el id que tenia asignat
        if ($book) {
            $existingAuthor = $book->getAuthor();
            $existingAuthorId = $existingAuthor ? $existingAuthor->getId() : null;
        }

        // Si l'autor que tenia asignat es diferent al nou autor,
        if ($newAuthorId != $existingAuthorId) {
            // Si no ha asignat a cap autor, pero en cambi si que ten tenia un abans, el deslinkem
            if (!$newAuthorId) {
                $existingAuthor->removeBook($book);
            }
            // En cambi, si realment ha asignat a un autor, el busquem i el retornem.
            else {
                return ($this->getAuthor)($newAuthorId);
            }
        }
        // Retornem null si el autor que tenia asignat i el nou, son el mateix
        return null;
    }
}
