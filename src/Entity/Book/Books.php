<?php

namespace App\Entity\Book;

use App\Entity\Book;
use App\Entity\Category\Categories;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Collection;

final class Books extends ArrayCollection
{
	public function __construct(Book ...$books)
	{
		parent::__construct($books);
	}
    
	public function toArray()
	{
		$books = [];

		foreach ($this as $book) {
			$books[] = $book->toArray();
        }

		return $books;
	}
}
