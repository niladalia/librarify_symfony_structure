<?php

namespace App\Service\Category;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CategoryCreator
{
    private $validator;
    private $category_rep;
    public function __construct(ValidatorInterface $validator, CategoryRepository $category_rep)
    {
        $this->validator = $validator;
        $this->category_rep = $category_rep;
    }

    public function __invoke(string $name): Category
    {
        $category = new Category(Uuid::uuid4());
        $category->setName($name);
        $errors = $this->validator->validate($category);
        if (count($errors) > 0) {
            $validation_errors = (string) $errors;
            throw new HttpException(400,$validation_errors);
        }
        $this->category_rep->save($category);

        return $category;
    }
}
