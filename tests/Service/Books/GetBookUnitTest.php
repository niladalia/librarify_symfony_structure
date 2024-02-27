<?php

namespace App\Test\Service\FileUploader;

use App\Entity\Book;
use App\Entity\Book\Title;
use App\Form\Model\BookDto;
use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;
use App\Service\Book\BookFinder;
use App\Service\FileUploader\FileUploaderS3;
use App\Tests\Mother\BookMother;
use League\Flysystem\FilesystemOperator;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookFinderUnitTest extends KernelTestCase
{
    private $bookRep;
    private $BookFinder;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRep = $this->createMock(BookRepository::class);

        $this->BookFinder = new BookFinder(
            $this->bookRep
        );
    }

    public function test_it_find_existing_book()
    {
        $id_uuid = Uuid::uuid4();
        $existingBook = BookMother::create($id_uuid, new Title("Title"));

        $this->bookRep->expects(self::exactly(1))
            ->method('find')
            ->willReturn($existingBook);

        $book = ($this->BookFinder)($id_uuid->serialize());

        $this->assertNotEmpty($book);
        $this->assertSame($existingBook, $book);
    }

    public function test_it_throws_exception_when_book_not_found()
    {
        $id_uuid = Uuid::uuid4();
        
        $this->bookRep->expects(self::exactly(1))
            ->method('find')
            ->willReturn(null);
        
        $this->expectException(BookNotFound::class);
        
        ($this->BookFinder)($id_uuid->serialize());

    }

}