<?php

namespace App\Controller\Api;

use App\Form\Model\AuthorDto;
use App\Repository\AuthorRepository;
use App\Service\Author\AuthorCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends AbstractController
{
    private $author_rep;
    public function __construct(AuthorRepository $author_rep)
    {
        $this->author_rep = $author_rep;
    }

    public function index(): Response
    {
        return $this->json($this->author_rep->findAllSerialized(), status: 200);
    }

    public function post(HttpFoundationRequest $request, AuthorCreator $author_creator): Response
    {
        $params = json_decode($request->getContent(), true);
        $author = $author_creator(
            new AuthorDto($params['name'])
        );
        return $this->json($author, status: 200);
    }
}
