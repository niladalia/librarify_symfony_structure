<?php

namespace App\Service\Book;

use App\Entity\Book\Description;
use App\Entity\Book\Score;
use App\Entity\Book\Title;
use App\Entity\Category;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Repository\BookRepository;
use App\Form\Type\BookFormType;
use App\Interfaces\FileUploaderInterface;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use App\Service\Category\CategoryCreator;
use App\Service\Category\GetCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Console\Descriptor\Descriptor;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookEditor
{
    private $book_rep;
    private $formFactory;
    private $fileUploader;
    private $getBook;
    private $updateBookCategory;
    private $updateBookAuthor;
    public function __construct(
        BookRepository $book_rep,
        FormFactoryInterface $formFactory,
        FileUploaderInterface $fileUploader,
        GetBook $getBook,
        UpdateBookCategory $updateBookCategory,
        UpdateBookAuthor $updateBookAuthor
    ) {
        $this->book_rep = $book_rep;
        $this->formFactory = $formFactory;
        $this->fileUploader = $fileUploader;
        $this->getBook = $getBook;
        $this->updateBookCategory = $updateBookCategory;
        $this->updateBookAuthor = $updateBookAuthor;
    }

    public function __invoke(Request $request, string $id): array
    {
        $book = ($this->getBook)($id);

        $bookDto = BookDto::createFromBook($book);

        $original_categories_dto = new ArrayCollection();
        /*
         Recorrem totes les categories que el llibre te asignades originalment.
         Aixó es tindria que fer en algún metode de BookHelper on poguessim obtindre totes les categories d'un determinat llibre
        */
        foreach ($book->getCategories() as $category) {
            # Creem DTO de category per tal de asignar-les al llibre DTO que acabem de crear  (realment cal la category DTO?)
            $categoryDto = CategoryDto::createFromCategory($category);
            $bookDto->categories[] = $categoryDto;
            # Afegim la category al array original_categories
            $original_categories_dto->add($categoryDto);
        }

        /*
         IMPORTANT -> Aquí carguem les NOVES categories que afegim al DTO (a més del title o altres parametres). ???En el foreach anterior hem afegit al DTO
         les categories que ja tenia cargada el book, per aquó afegim les noves que provenen de la request ??? . <<- en tindriem moltes de duplicades!

         Explicació
         Cuan creem el Factory en base a la request :
         Si aquest detecta que en la request hi ha un Category que JA ESTA asignat ( en base al name ) al bookDto,
         sobreescriu el DTO i li asigna un id, si el DTO tenia asignat un category que NO esta a la request es borrara del DTO.
         Si  aquest detecta que en la request hi ha un Category que no conté el bookDto,
         li asigna  amb el ID null (posterior ment es creara nova category).

         En definitiva, el formulari sobreescriu els category que hem asignat al DTO en el bucle,
         a excepció de si detecta que un category ja existia, modifica el DTO per asignarli el ID.


        */
        $content = json_decode($request->getContent(), true);
        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->submit($content);

        if (!$form->isSubmitted()) {
            return [null, 'Bad parameters!'];
        }
        if (!$form->isValid()) {
            return [null, "Data is not valid"];
        }

        ($this->updateBookCategory)($original_categories_dto, $bookDto, $book);
        $new_author = ($this->updateBookAuthor)($bookDto->author_id, $book);

        $book->update(
            new Title($bookDto->title),
            $bookDto->base64Image ? $this->fileUploader->uploadFile($bookDto) : null,
            $new_author ? $new_author : null,
            new Description($bookDto->description),
            new Score($bookDto->score)
        );
        $this->book_rep->save($book);

        $serialized_book = $this->book_rep->returnBookSerialized($book);
        return [$serialized_book, null];
    }
}
