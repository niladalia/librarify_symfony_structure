<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Entity\Author\Authors;
use App\Model\Exception\Author\AuthorNotFound;
use App\Repository\AuthorRepository;
use Ramsey\Uuid\Uuid;

class AuthorsFinder
{
    private $author_rep;

    public function __construct(AuthorRepository $author_rep)
    {
        $this->author_rep = $author_rep;
    }


    public function __invoke(): ?Authors
    {
        $books = $this->author_rep->find_all();

        return $books;
    }
}
