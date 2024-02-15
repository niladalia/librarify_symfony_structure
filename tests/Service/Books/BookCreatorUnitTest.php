<?php

namespace App\Test\Service\Book;

use App\Form\Model\BookDto;
use App\Service\FileUploaderS3;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class BookCreatorUnitTest extends KernelTestCase
{
    public function testSuccessBookCreation()
    {
        $this->markTestSkipped('PHPUnit will skip this test method');
        /*
                EntityManagerInterface $em,
        FileUploaderInterface $fileUploader,
        FormFactoryInterface $formFactory

        $fileSystem = $this->createMock(EntityManagerInterface::class);
        $fileSystem
            ->expects(self::exactly(1))
            ->method('persist')
            ->with();




        $request = Request::create(
            '/', 'POST', array("title"=>"LIBRO "), [], [], ['HTTP_ACCEPT' => 'application/xml;q=0.9, application/json']
          );
      */
    }

    public function testCreateBookWithoutContent()
    {
        $this->markTestSkipped('PHPUnit will skip this test method');
    }
}
