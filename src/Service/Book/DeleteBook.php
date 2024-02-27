<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Repository\BookRepository;
use App\Form\Type\BookFormType;
use App\Interfaces\FileUploaderInterface;
use App\Model\Exception\Book\BookNotFound;
use App\Service\DeleteFile;
use App\Service\FileDeleter;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteBook
{

    public function __construct
    (
        private BookRepository $bookRep,
        private DeleteFile $file_deleter,
        private BookFinder $BookFinder
    ) {
        $this->bookRep = $bookRep;
        $this->file_deleter = $file_deleter;
        $this->BookFinder = $BookFinder;
    }


    public function __invoke(string $id): void
    {
        $book = ($this->BookFinder)($id);
        if (!$book) {
            BookNotFound::throw();
        }
        if ($book->getImage() != null) {
            ($this->file_deleter)($book->getImage());
        }
        $this->bookRep->delete($book);
    }
}
