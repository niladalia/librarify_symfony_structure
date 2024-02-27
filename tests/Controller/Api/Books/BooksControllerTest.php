<?php

namespace App\Test\Controller\Api;


use App\Model\Exception\Author\AuthorNotFound;
use App\Repository\AuthorRepository;
use App\Service\FileUploaderLocal;
use App\Tests\Mother\AuthorMother;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BooksControllerTest extends WebTestCase
{
    
    public function testBookFinders()
    {
        #$this->markTestSkipped( 'PHPUnit will skip this test method' );
        $client = static::createClient();
        $client->request('GET', 'api/books', [], [], ['HTTP_X-AUTH-TOKEN' => 'Librarify', 'CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json']);
        $this->assertResponseStatusCodeSame(200);
        #$this->assertEquals(200,$client->getResponse()->getStatusCode());
    }


    public function testCreateComplexBookSuccess()
    {

        $client = static::createClient();
        
        // Persistim un autor en BD perque pugui adjuntar el book a un author existent
        $author = AuthorMother::create(Uuid::fromString("59aa8278-bd4a-4895-a9e1-5684c89a3627"));
        $authorRep = static::getContainer()->get(AuthorRepository::class);
        $authorRep->save($author);

        $client->request(
            'POST',
            'api/books',
            [
                "title" => "LIBRO",
                "score" => 3,
                "description" => "Description",
                "author_id" => "59aa8278-bd4a-4895-a9e1-5684c89a3627"
            ],
            [],
            ['CONTENT_TYPE' => 'application/json', "HTTP_X-AUTH-TOKEN" => 'Librarify'],
            ''
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    
    public function testCreateSimpleBookSuccess()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'api/books',
            ["title" => "LIBRO"],
            [],
            ['CONTENT_TYPE' => 'application/json', "HTTP_X-AUTH-TOKEN" => 'Librarify'],
            ''
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateBookWithoutContent()
    {
        $client = static::createClient();

        $client->request('POST', 'api/books', [], [], ["HTTP_X-AUTH-TOKEN" => 'Librarify']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testCreateBookInvalidData()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            'api/books',
            ['title' => ''],
            [],
            ['CONTENT_TYPE' => 'application/json', "HTTP_X-AUTH-TOKEN" => 'Librarify'],
            '{"title":""}'
        );
        $this->assertResponseStatusCodeSame(400);
    }

    public function testExpectAuthorNotFound()
    {

        $client = static::createClient();
        $client->request(
            'POST',
            'api/books',
            [
                "title" => "LIBRO",
                "author_id" => "59aa8278-bd4a-4895-a9e1-5684c89a3628"
            ],
            [],
            ['CONTENT_TYPE' => 'application/json', "HTTP_X-AUTH-TOKEN" => 'Librarify'],
            ''
        );
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
#        $this->expectException(AuthorNotFound::class);
    }



}
