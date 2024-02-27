<?php

namespace App\Entity;

use App\Entity\Author;
use App\Entity\Book\Description;
use App\Entity\Book\Score;
use App\Entity\Book\Title;
use App\Entity\Category;
use App\Entity\Category\Categories;
use App\Event\BookCreatedDomainEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface as UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

class Book
{
    private UuidInterface $id;


    private Title $title;


    private ?string $image = null;


    private ?int $pages = null;

    private ?Score $score;

    private ?Description $description;

    /**
     * @var  Collection<int, Category>
    */

    private Collection $categories;

    private array $domainEvents;

    private ?Author $author = null;

    public function __construct(
        UuidInterface $uuid,
        Title $title,
        ?string $image,
        ?Author $author,
        ?Description $description =  new Description(),
        ?Score $score = new Score()
    ) {
        $this->id = $uuid;
        $this->title = $title;
        $this->image = $image;
        $this->author = $author;
        $this->description = $description;
        $this->score = $score;
        $this->categories = new ArrayCollection();
    }

    public static function create(
        Title $title,
        ?string $filename,
        ?Author $author,
        ?Description $description,
        ?Score $score
    ): self {
        $book = new self(
            Uuid::uuid4(),
            $title,
            $filename,
            $author,
            $description,
            $score
        );
        // Aquí creem el Event de domini indicant el tipus (Book created Domain event)
        $book->addDomainEvent(new BookCreatedDomainEvent($book->getId()));
        return $book;
    }

    public function update(
        ?Title $title,
        ?string $filename,
        ?Author $author,
        ?Description $description,
        ?Score $score
    ) {
        $this->title = $title;
        $filename ? $this->image = $filename : null;
        $this->description = $description;
        $this->score = $score;
        $this->author = $author;
    }

    public function addDomainEvent(Event $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function pullDomainEvents(): array
    {
        return $this->domainEvents;
    }
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function setTitle(Title $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function toArray()
    {
        $categories =  new Categories(...$this->getCategories());

        return [
            'id' => $this->getId()->serialize(),
            'title' => $this->getTitle()->getValue(),
            'score' => $this->getScore()->getValue(),
            'description' => $this->getDescription()->getValue(),
            'categories' => $categories->toArray(),
            'author' => $this->getAuthor() ? $this->getAuthor()->toArray() : null
        ];
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(?int $pages): static
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function setScore(?Score $score)
    {
        $this->score = $score;

        return $this;
    }

    public function getScore(): Score
    {
        return $this->score;
    }

    public function getDescription(): Description
    {
        return $this->description;
    }


    public function setDescription(?Description $description)
    {
        $this->description = $description;

        return $this;
    }
}
