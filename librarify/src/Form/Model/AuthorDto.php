<?php

namespace App\Form\Model;

class AuthorDto
{
    public $title;
    public $books;


    public function __construct()
    {
        $this->books = [];
    }
}
