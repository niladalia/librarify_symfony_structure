<?php

namespace App\Form\Model;

class AuthorDto
{
    public function __construct(public ?string $name = null)
    {
        $this->name = $name;
    }
}
