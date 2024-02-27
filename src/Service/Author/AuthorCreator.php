<?php

namespace App\Service\Author;

use App\Entity\Author;
use App\Entity\Author\AuthorName;
use App\Form\Model\AuthorDto;
use App\Repository\AuthorRepository;

class AuthorCreator
{
    public function __construct(
        private AuthorRepository $author_rep
    ) {
        $this->author_rep = $author_rep;
    }

    public function __invoke(AuthorDto $authorDto): Author
    {
        $author = Author::create(new AuthorName($authorDto->name));

        $this->author_rep->save($author);

        return $author;
    }
}
