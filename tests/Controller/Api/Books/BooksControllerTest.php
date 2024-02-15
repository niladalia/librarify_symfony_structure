<?php

namespace App\Test\Controller\Api;

use App\Form\Model\BookDto;
use App\Service\FileUploaderLocal;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BooksControllerTest extends WebTestCase
{
    public function testGetBooks()
    {
        #$this->markTestSkipped( 'PHPUnit will skip this test method' );
        $client = static::createClient();
        $client->request('GET', 'api/books', [], [], ['HTTP_X-AUTH-TOKEN' => 'Librarify', 'CONTENT_TYPE' => 'application/json', 'ACCEPT' => 'application/json']);
        $this->assertResponseStatusCodeSame(200);
        #Alternativa
        #$this->assertEquals(200,$client->getResponse()->getStatusCode());
    }

    /*
    Revisar perque si li paso els parametres en la variable 'parameters' funciona pero si li paso els parametres en el content, no funciona
    EL mateix pasa amb postman, si li paso el titol al Raw igual que en el tutorial, no funciona.
    Primer investigar lo del Postman ...
    */
    public function testCreateBookSuccess()
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

        #$this->assertResponseStatusCodeSame(200);
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
}
