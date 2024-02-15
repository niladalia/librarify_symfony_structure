<?php
namespace App\Service\Category;

use App\Entity\Category;
use App\Model\Exception\Category\CategoryNotFound;
use App\Repository\CategoryRepository;
use Ramsey\Uuid\Uuid;

class GetCategory {

    private $category_rep;

    public function __construct(CategoryRepository $category_rep)
    {
        $this->category_rep = $category_rep;
    }

    public function __invoke(string $id): ?Category
    {
        $category =  $this->category_rep->find(Uuid::fromString($id));
        if(!$category){
            CategoryNotFound::throw($id);
        }
        return $category;
    }
}