<?php

namespace App\Controller\Api;

use App\Form\Model\AuthorDto;
use App\Repository\AuthorRepository;
use App\Service\Author\AuthorCreator;
use App\Service\Author\AuthorsFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends AbstractController
{
    private $author_rep;
    public function __construct(AuthorRepository $author_rep)
    {
        $this->author_rep = $author_rep;
    }

    public function index(AuthorsFinder $authorsFinder): JsonResponse
    {
        $books = $authorsFinder->__invoke();

        return new JsonResponse(
            $books->toArray()
        );
    }

    public function post(HttpFoundationRequest $request, AuthorCreator $author_creator): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $author = $author_creator(
            new AuthorDto($params['name'])
        );
        return new JsonResponse(
            $author->toArray()
        );
    }
}
