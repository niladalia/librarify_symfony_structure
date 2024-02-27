<?php

namespace App\Entity\Author;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category\Categories;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Collection;

final class Authors extends ArrayCollection
{
    public function __construct(Author ...$authors)
    {
        parent::__construct($authors);
    }

    public function toArray(): array
    {
        $authors = [];

        foreach ($this as $author) {
            $authors[] = $author->toArray();
        }

        return $authors;
    }
}
