<?php

declare(strict_types=1);

namespace App\Model\Responses;

use App\Entity\Book;

final readonly class BookResponse
{

	public function __construct(private array $books) {

    }

    public function books(): array
    {
        $books_array = [];
        foreach ($this->books as $book) {
            $books_array[] = 
            [
                'id' => $book->getId(),
                'name' => $book->getTitle(),
                'score' => $book->getScore()->getValue(),
                'description' => $book->getDescription()->getValue(),
            ];
        };
        return $books_array;
    }
}