<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findAllSerialized()
    {
        $author_array = [];

        $all_authors = $this->findBy([]);

        foreach ($all_authors as $author) {
            $author_array[] = $this->returnBookSerialized($author);
        };

        return $author_array;
    }

    public function returnBookSerialized(Author $author)
    {
        $books_array = $this->BookFindersSerializer($author);

        return [
             'id' => $author->getId(),
             'name' => $author->getName(),
             'books' => $books_array
        ];
    }

    private function BookFindersSerializer(Author $author): array
    {
        $books = $author->BookFinders()->getValues();
        $books_array = [];
        foreach ($books as $book) {
            $books_array[] = ['id' => $book->getId(), 'title' => $book->getTitle()];
        }
        return $books_array;
    }

    public function save(Author $author): Author
    {
        $this->getEntityManager()->persist($author);
        $this->getEntityManager()->flush();
        return $author;
    }

    public function reload(Author $author): Author
    {
        $this->getEntityManager()->refresh($author);
        return $author;
    }

    //    /**
    //     * @return Author[] Returns an array of Author objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Author
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
