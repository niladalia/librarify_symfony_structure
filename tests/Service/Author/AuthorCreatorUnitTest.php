<?php

namespace App\Test\Service\Author;

use App\Entity\Author;
use App\Form\Model\AuthorDto;
use App\Model\Exception\Generic\InvalidArgument;
use App\Repository\AuthorRepository;
use App\Service\Author\AuthorCreator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;

class AuthorCreatorUnitTest extends KernelTestCase
{
    private $authorRep;
    private $authorCreator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorRep = $this->createMock(AuthorRepository::class);

        $this->authorCreator = new AuthorCreator(
            $this->authorRep
        );
    }

    public function test_it_creates_an_author()
    {
        # $this->markTestSkipped('PHPUnit will skip this test method');
        $authorDto = new AuthorDto(
            "J.K. Rowling"
        );

        $this->authorRep
        ->expects(self::exactly(1))
        ->method('save')
        ->willReturnCallback(function (Author $authorCallback) {
            return $authorCallback;
        });

        $author = $this->authorCreator->__invoke($authorDto);

        $this->assertEquals("J.K. Rowling", $author->getName()->getValue());
        $this->assertInstanceOf(Author::class, $author);
    }

    public function test_it_throws_exception_when_data_is_invalid()
    {
        $authorDto = new AuthorDto("A");

        $this->expectException(InvalidArgument::class);

        $this->authorCreator->__invoke($authorDto);
    }
}
