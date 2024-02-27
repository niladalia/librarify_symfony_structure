<?php

namespace App\Form\Model;

use App\Entity\Book;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class BookDto
{
    public function __construct(
        public ?string $title = null,
        public ?string $base64Image = null,
        public ?array $categories = [],
        public ?string $author_id = null,
        public ?int $score = null,
        public ?string $description = null
    ) {
        $this->title = $title;
        $this->base64Image = $base64Image;
        $this->author_id = $author_id;
        $this->score = $score;
        $this->description = $description;
        $this->categories = [];
    }

    public static function createFromBook(Book $book)
    {
        $dto = new self();
        $dto->title = $book->getTitle()->getValue();
        return $dto;
    }
}
