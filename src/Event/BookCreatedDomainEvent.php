<?php

namespace App\Event;

use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

class BookCreatedDomainEvent extends Event
{
    public const NAME = 'book.created';

    public function __construct(public UuidInterface $bookId)
    {
        $this->bookId = $bookId;
    }
}
