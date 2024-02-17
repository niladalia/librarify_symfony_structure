<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Book\Score;
use App\Form\Model\BookDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findAllSerialized()
    {
        $books_array = [];

        $all_books = $this->findBy([]);

        foreach ($all_books as $book) {
            $books_array[] = $this->returnBookSerialized($book);
        };

        return $books_array;
    }

    public function returnBookSerialized(Book $book)
    {
        $categories_array = $this->getCategoriesSerialized($book);
        $author = $this->getAuthorSerialized($book);
        return [
             'id' => $book->getId(),
             'title' => $book->getTitle()->getValue(),
             'score' => $book->getScore()->getValue(),
             'description' => $book->getDescription()->getValue(),
             'categories' => $categories_array,
             'author' => $author
        ];
    }

    private function getCategoriesSerialized(Book $book): array
    {
        $categories = $book->getCategories()->getValues();
        $categories_array = [];
        foreach ($categories as $category) {
            $categories_array[] = ['id' => $category->getId(), 'name' => $category->getName()];
        }
        return $categories_array;
    }

    private function getAuthorSerialized(Book $book): array
    {
        $return_array = [];
        if ($book->getAuthor()) {
            $return_array = [
                "id" => $book->getAuthor()->getId(),
                "name" => $book->getAuthor()->getName()
            ];
        }

        return $return_array;
    }
    /*
     Aquí creem els metodes que es pugui reutilitzar en un futur. EL metode createBook aquí no te sentit
     ja que només s'utilitzara un cop des d'un servei ( BookCreator ), pero el save() si que es pot utilitzar
     tant des de create com edit, així com el delete.

     8cabe175-a76f-4027-9e63-03e199e84136
    */

    public function save(Book $book): Book
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
        return $book;
    }

    public function reload(Book $book): Book
    {
        $this->getEntityManager()->refresh($book);
        return $book;
    }


    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
