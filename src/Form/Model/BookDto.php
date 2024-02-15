<?php

namespace App\Form\Model;

use App\Entity\Book;

class BookDto
{
    public $title;
    public $base64Image;
    public $categories;
    public $author_id;
    public $score;
    public $description;

    public function __construct()
    {
        $this->categories = [];
    }

    public static function createFromBook(Book $book)
    {
        $dto = new self();
        $dto->title = $book->getTitle();
        return $dto;
    }
}
