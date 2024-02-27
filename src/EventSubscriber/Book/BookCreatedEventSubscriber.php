<?php

namespace App\EventSubscriber\Book;

use App\Event\BookCreatedDomainEvent;
use App\Service\Book\BookFinder;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookCreatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private BookFinder $BookFinder, private LoggerInterface $logger) {}

    public static function getSubscribedEvents(): array
    {
        return [
            BookCreatedDomainEvent::class => [
                ['logCreation', 0],
                ['sendMail', 10]
            ],
        ];
    }

    public function logCreation(BookCreatedDomainEvent $event)
    {
        $book = ($this->BookFinder)($event->bookId->toString());
        $this->logger->info(sprintf('Book Created: %s', $book->getTitle()->getValue()));
    }

    public function sendMail(BookCreatedDomainEvent $event)
    {
        // ...
    }
}
