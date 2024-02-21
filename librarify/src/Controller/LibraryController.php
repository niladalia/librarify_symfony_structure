<?php

namespace App\Controller;

use App\Entity\Book;
use Ramsey\Uuid\Uuid;
use Psr\Log\LoggerInterface;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LibraryController extends AbstractController
{
    private $logger;
    private $em;
    private $book_rep;

    # Esta es la el controlador de prueba donde podemos ver varias cosas sobre symfony, estructura general, rutas,
    #inyección de dependencias, acceso a los Repository, persistencia DB...
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, BookRepository $book_rep)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->book_rep = $book_rep;
    }

    #   #[Route('/library/list', name: 'library_list')]
    public function list(Request $request)
    {
        $books_array = [];
        $match_book = [];
        $title = $request->get('title', "-");

        $this->logger->info("-------");
        $this->logger->info("The title name is : $title");
        $this->logger->info("-------");

        $match_book = $this->book_rep->findBy(['title' => $title]);

        if (empty($match_book)) {
            $match_book = $this->book_rep->findAll();
        }


        foreach ($match_book as $book) {
            $books_array[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle()
            ];
        };

        return $this->json([
            'success' => 'true',
            'data' => $books_array
        ]);
    }

    public function createBook(Request $request)
    {
        $title = $request->get('title', '-');
        $pages = $request->get('pages', null);
        $img = $request->get('img', null);

        $book = new Book(Uuid::uuid4());
        $book->setTitle($title);
        $book->setPages($pages);
        $book->setImage($img);

        $this->em->persist($book);
        $this->em->flush();

        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id' => $book->getId(),
                    'title' => $book->getTitle()
                ]
            ]
        ]);
        return $response;
    }
}
