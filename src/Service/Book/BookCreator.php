<?php

namespace App\Service\Book;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Book\Description;
use App\Entity\Book\Score;
use App\Entity\Book\Title;
use App\Form\Model\BookDto;
use App\Repository\BookRepository;
use App\Form\Type\BookFormType;
use App\Interfaces\FileUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use stdClass;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BookCreator
{
    private $fileUploader;
    private $em;
    private $formFactory;
    private $book_rep;
    private $updateBookAuthor;
    private $eventDispatcher;
    public function __construct(
        EntityManagerInterface $em,
        FileUploaderInterface $fileUploader,
        FormFactoryInterface $formFactory,
        BookRepository $book_rep,
        UpdateBookAuthor $updateBookAuthor,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->fileUploader = $fileUploader;
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->book_rep = $book_rep;
        $this->updateBookAuthor = $updateBookAuthor;
        $this->eventDispatcher = $eventDispatcher;
    }

    /*
    Retorna un array de dos posicions. La primera posició es l'objecte Book
    i la segona posició l'error
    [Book/NULL,Error/NULL]
    */

    public function __invoke(Request $request): Book
    {
        /* DTO :
           Utilitzem un objecte DTO ja que tenim que treballar amb el camp base64Image.
           Per estalviar-nos tenir que crearlo a la BD o al Entity, creem el objecte DTO
           on podrem crear tots els camps que volgue per moldejar l'objecte.

           FORM:
           Crear formularis per als objectes ens permet poder asignar els valors directament des de la request fins al objecte
           (en aquest cas DTO cosa que ens obliga a asignarlos altra vegada) sense que tinguem que asignarlos manualment un a un.
           A mes a mes també ens proporciona un validador de dades configurable a un .yaml

           NOTA:
           Per a complir amb la arquitectura hexagonal, la major part d'aquest codi tindria que estar
           en un caso de uso i no al controlador.
        */

        $bookDto = new BookDto();
        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            throw new HttpException(400,'Object is not valid');
        }

        $author = ($this->updateBookAuthor)($bookDto->author_id);
        $filename = $bookDto->base64Image ? $this->fileUploader->uploadFile($bookDto) : null;

        if ($bookDto->base64Image) {
            $filename = $this->fileUploader->uploadFile($bookDto);                
        }

        $book = Book::create(
            new Title($bookDto->title),
            $filename,
            $author,
            new Description($bookDto->description),
            new Score($bookDto->score),
        );
        $this->book_rep->save($book);

        // Aquí llançem tots els events de domini que haguem creat en el domini.
        #$this->eventDispatcher->dispatch(...$book->pullDomainEvents());
        foreach ($book->pullDomainEvents() as $event) {
            $this->eventDispatcher->dispatch($event);
        }
        return $book;
    }
}
