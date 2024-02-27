<?php

namespace App\Entity;

use App\Entity\Author\AuthorName;
use App\Entity\Book;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Author
{
    public $id;
    public $name;
    /**
     * @var  Collection<int, Book>
    */

    private Collection $books;

    public function __construct(UuidInterface $uuid,  ?AuthorName $name = new AuthorName())
    {
        $this->id = $uuid;
        $this->name = $name;
        $this->books = new ArrayCollection();
    }


    public static function create(
        AuthorName $name
    ): self {
        $author = new self(
            Uuid::uuid4(),
            $name
        );

        return $author;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?AuthorName
    {
        return $this->name;
    }

    public function setName(?AuthorName $name): static
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
            $book->setAuthor($this);
        }

        return $this;
    }

    public function toArray(){
        return  [
            "id" => $this->getId()->serialize(),
            "name" => $this->getName()->getValue()
        ];
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }
}
