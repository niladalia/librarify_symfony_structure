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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

//TEst
class BooksController extends AbstractController
{
    public function __construct(private BookRepository $book_rep, private FormFactoryInterface $formFactory)
    {
        $this->book_rep = $book_rep;
        $this->formFactory = $formFactory;
    }

    public function get(BooksFinder $booksFinder): JsonResponse
    {
        $books = $booksFinder->__invoke();

        return new JsonResponse(
            $books->toArray()
        );
    }

    public function getById(string $id, BookFinder $bookFinder): JsonResponse
    {
        $book = $bookFinder->__invoke($id);

        return new JsonResponse(
            $book->toArray()
        );
    }

    public function post(Request $request, BookCreator $bookCreator): JsonResponse
    {
        /*
           DTO :
           Utilitzem un objecte DTO ja que tenim que treballar amb el camp base64Image.
           Per estalviar-nos tenir que crearlo a la BD o al Entity, creem el objecte DTO
           on podrem crear tots els camps que volguem per moldejar l'objecte.

           FORM:
           Crear formularis per als objectes ens permet poder asignar els valors directament des de la request fins al objecte
           (en aquest cas DTO cosa que ens obliga a asignarlos altra vegada) sense que tinguem que asignarlos manualment un a un.
           A mes a mes també ens proporciona un validador de dades configurable a un .yaml
        */

        $bookDto = new BookDto();
        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new HttpException(400, 'Object is not valid');
        }

        $book = ($bookCreator)($bookDto);

        return new JsonResponse(
            $book->toArray()
        );
    }

    public function put(Request $request, string $id, BookEditor $bookEditor): JsonResponse
    {
        $book = ($bookEditor)($request, $id);

        return new JsonResponse(
            $book->toArray()
        );
    }

    public function patch(string $id, Request $request, BookFieldUpdater $bookFieldUpdater): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $book = ($bookFieldUpdater)($data, $id);

        return new JsonResponse(
            $book->toArray()
        );
    }

    public function delete(string $id, DeleteBook $deleteBook): Response
    {
        ($deleteBook)($id);

        return new Response('', Response::HTTP_OK);
    }
}
