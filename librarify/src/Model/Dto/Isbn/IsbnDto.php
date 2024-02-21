<?php

namespace App\Model\Dto\Isbn;

class IsbnDto
{
    public string $title;
    public string $key;
    public string $publishDate;

    public function __construct(string $title, string $key, string $publishDate)
    {
        $this->title = $title;
        $this->key = $key;
        $this->publishDate = $publishDate;
    }
}
