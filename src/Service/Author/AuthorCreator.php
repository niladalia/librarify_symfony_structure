<?php

namespace App\Service\Author;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Author;
use App\Entity\Author\AuthorName;
use App\Form\Model\AuthorDto;
use App\Model\Exception\Generic\InvalidData;
use App\Repository\AuthorRepository;

class AuthorCreator
{

    public function __construct
    (
        private AuthorRepository $author_rep
    )
    {
        $this->author_rep = $author_rep;
    }

    public function __invoke(AuthorDto $authorDto): Author
    {
        $author = Author::create(new AuthorName($authorDto->name));        

        $this->author_rep->save($author);

        return $author;
    }

    /* 
     Old invoke utilitzant el validator(), a mode d'exemple. He tret el validator ja que 
     es de symfony i els components de symfony no tindrien que estar a Aplicació.
     A mes a mes, es imposible fer correr els test correctament amb el return del validate()
     La dependencia es private ValidatorInterface $validator
        public function old__invoke(AuthorDto $authorDto): Author
        {
            $author = Author::create(new AuthorName($authorDto->name));

            $errors = $this->validator->validate($author);
            if (count($errors) > 0) {
                $validation_errors = (string) $errors;
                InvalidData::throw($validation_errors);
            }
            $author = $this->author_rep->save($author);

            return $author;
        }
    */

}


