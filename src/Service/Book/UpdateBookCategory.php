<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Service\Category\CategoryCreator;
use App\Service\Category\GetCategory;
use Doctrine\Common\Collections\ArrayCollection;

use Ramsey\Uuid\Uuid;

class UpdateBookCategory
{
    private $getCategory;
    private $categoryCreator;
    public function __construct(GetCategory $getCategory,CategoryCreator $categoryCreator)
    {
        $this->getCategory = $getCategory;
        $this->categoryCreator = $categoryCreator;
    }


    public function __invoke(ArrayCollection $original_categories_dto,BookDto $bookDto,Book $book)
    {
        // Si cal, borrem la categoria
        foreach ($original_categories_dto as $category_dto) {
            
            if (!in_array($category_dto, $bookDto->categories)) {
                $category = ($this->getCategory)($category_dto->id);
                if ($category !== null){
                    $book->removeCategory($category);
                }
            }
        }
        // Creem la categoria si no existeix
        foreach ($bookDto->categories as $new_category_dto) {

            $category = null;
            if (!$original_categories_dto->contains($new_category_dto)) {            
                if ($new_category_dto->id != null) {
                    $category = ($this->getCategory)($new_category_dto->id);
                }
                if (!$category) {
                    $category = ($this->categoryCreator)($new_category_dto->name);
                }
                $book->addCategory($category);
            }
        }
    }
}
