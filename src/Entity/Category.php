<?php

namespace App\Entity;

use App\Entity\Book;
use App\Entity\Category\CategoryName;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

/*
Category te una relacion ManyToMany amb llibres es a dir, una categoria pot contindre varios llibres i
un llibre pot pertanyer a varies categories. Pero si ens fixem en els DTO o els FormType de cada objecte, veiem que
l'unic lloc on es fa referencia al Categories i lunic lloc on es poden crear categories es a traves dels Books.
Podem crear un book i asignarlo directament a una categoria pero no podem crear una categoria i asignarla directament a un book.
*/

class Category
{
    /**
     * @var  Collection<int, Book>
    */

    private Collection $books;

    public function __construct(private UuidInterface $id, private ?CategoryName $name = new CategoryName())
    {
        $this->id = $id;
        $this->name = $name;
        $this->books = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?CategoryName
    {
        return $this->name;
    }

    public function setName(CategoryName $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function BookFinders(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->addCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            $book->removeCategory($this);
        }

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId()->serialize(),
            'name' => $this->getName()->getValue()
        ];
    }
}
