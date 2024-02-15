<?php

namespace App\Controller\Api;

use App\Model\Responses\BookResponse;
use App\Repository\BookRepository;
use App\Service\Book\BookCreator;
use App\Service\Book\BookEditor;
use App\Service\Book\DeleteBook;
use App\Service\Book\GetBook;
use App\Service\Book\BookFieldUpdater;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BooksController extends AbstractController
{
    private $book_rep;

    public function __construct(BookRepository $book_rep)
    {
        $this->book_rep = $book_rep;
    }

    /*
     Esta be PERO no estem fent servir un serializer de veritat, el serializer, transforma tot el que vingui de $books, a JSON,
     aquí ho estic transformant JO a JSON. Pero de moment ho deixem així,  no es important
    */

    public function get(GetBook $getBook,?string $id = null): Response
    {
        $books = [];

        if (!empty($id)) {
            $books = $this->book_rep->returnBookSerialized(($getBook)($id));
        }
        if (!$books) {
            $books = $this->book_rep->findAllSerialized();
        }

        //return View::create($books,$error);
        return $this->json([
            'success' => 'true',
            'data' => $books
        ]);
    }


    /*
        Aquesta es una implementació perfecte on utilitzem un cas d'us o servei (BookCreator)
        per a gestionar la lógica i comunicar-se amb els diferents Entitats i Implementacions
    */
    public function post(Request $request, BookCreator $bookCreator)
    {
        $book = $bookCreator($request);
        return $this->json(data: $this->book_rep->returnBookSerialized($book), status : 200);
    }

    public function put(Request $request,string $id,BookEditor $bookEditor) {
        
        [$book,$error] = ($bookEditor)($request, $id);

        if (!$error) {
            $response = $this->json($book, status : 200);
        } else {
            $response  = $this->json(['message' => $error], status: 400);
        }
        return $response;
    }

    public function patch(string $id,Request $request,BookFieldUpdater $bookFieldUpdater){
        
        $book = $bookFieldUpdater($request,$id);
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
