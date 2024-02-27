<?php

namespace App\Service\Category;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Category;
use App\Entity\Category\CategoryName;
use App\Form\Model\CategoryDto;
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

    public function __invoke(CategoryDto $categoryDto): Category
    {
        $category = new Category
        (
            Uuid::uuid4(),
            new CategoryName($categoryDto->name)
        );

        $this->category_rep->save($category);

        return $category;
    }
}
