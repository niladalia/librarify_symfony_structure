<?php

namespace App\Controller\Api;

use App\Form\Model\BookDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Service\Book\BookCreator;
use App\Service\Book\BookEditor;
use App\Service\Book\DeleteBook;
use App\Service\Book\BookFinder;
use App\Service\Book\BookFieldUpdater;
use App\Service\Book\BooksFinder;
use PHPUnit\Util\Json;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

//TEst
class BooksController extends AbstractController
{
    public function __construct(private BookRepository $book_rep, private FormFactoryInterface $formFactory)
    {
        $this->book_rep = $book_rep;
        $this->formFactory = $formFactory;
    }

    public function get(BooksFinder $booksFinder): Response
    {
        $results = $booksFinder->__invoke();

        $books = $results->toArray();

        return $this->json([
            'success' => 'true',
            'data' => $books
        ]);
    }

    public function get_by_id(string $id, BookFinder $bookFinder): Response
    {
        $result = $bookFinder->__invoke($id);

        $book = $result->toArray();

        return $this->json([
            'success' => 'true',
            'data' => $book
        ]);
    }

    /*
        Aquesta es una implementació perfecte on utilitzem un cas d'us o servei (BookCreator)
        per a gestionar la lógica i comunicar-se amb els diferents Entitats i Implementacions
    */
    public function post(Request $request, BookCreator $bookCreator)
    {
        /*
           DTO :
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

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new HttpException(400, 'Object is not valid');
        }

        $book = $bookCreator($bookDto);
        return $this->json(data: $this->book_rep->returnBookSerialized($book), status : 200);
    }

    public function put(Request $request, string $id, BookEditor $bookEditor)
    {
        [$book,$error] = ($bookEditor)($request, $id);

        if (!$error) {
            $response = $this->json($book, status : 200);
        } else {
            $response  = $this->json(['message' => $error], status: 400);
        }
        return $response;
    }

    public function patch(string $id, Request $request, BookFieldUpdater $bookFieldUpdater)
    {
        $book = $bookFieldUpdater($request, $id);
        return $this->json($this->book_rep->returnBookSerialized($book), status : 200);
    }



    public function delete(Request $request, string $id, DeleteBook $deleteBook)
    {
        try {
            $deleteBook($id);
        } catch (Throwable $e) {
            return $this->json(['message' => $e->getMessage()], status: 400);
        }
        return $this->json("Book with id $id deleted", status : 200);
    }
}
