<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Model\Exception\Author\AuthorNotFound;
use App\Repository\AuthorRepository;
use Ramsey\Uuid\Uuid;

class GetAuthor
{
    private $author_rep;

    public function __construct(AuthorRepository $author_rep)
    {
        $this->author_rep = $author_rep;
    }


    public function __invoke(string $id): ?Author
    {
        $author = $this->author_rep->find(Uuid::fromString($id));
        if (!$author) {
            AuthorNotFound::throw($id);
        }
        return $author;
    }
}