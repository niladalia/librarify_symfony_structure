<?php

namespace App\Entity\Category;

use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;

final class Categories extends ArrayCollection
{
	public function __construct(Category ...$categories)
	{
		parent::__construct($categories);
	}
    
	protected function type(): string
	{
		return Category::class;
	}

	public function toArray(){
		$categories = [];

		foreach ($this as $category) {
			$categories[] = $category->toArray();
        }

		return $categories;
	}
}
