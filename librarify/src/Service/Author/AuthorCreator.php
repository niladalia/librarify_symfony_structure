<?php

namespace App\Service\Author;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthorCreator
{
    private $validator;
    private $author_rep;
    public function __construct(ValidatorInterface $validator, AuthorRepository $author_rep)
    {
        $this->validator = $validator;
        $this->author_rep = $author_rep;
    }

    // Clarament aquesta no es la millo forma de fer-ho ni validar ni llançament d'excepcións pero ho deixo per que serveixi d'exemple el tema de validació
    // EL correcte es fer la validacio en els propis value objects, tot i que aquest metode no esta malament
    public function __invoke(array $params): Author
    {
        $author = new Author(Uuid::uuid4());
        $author->setName($params['name']);
        $errors = $this->validator->validate($author);
        if (count($errors) > 0) {
            $validation_errors = (string) $errors;
            throw new HttpException(400, $validation_errors);
        }
        $author = $this->author_rep->save($author);

        return $author;
    }
}
