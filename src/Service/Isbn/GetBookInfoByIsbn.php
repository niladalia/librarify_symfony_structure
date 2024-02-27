<?php

namespace App\Service\Isbn;

use App\Interfaces\HttpClientInterface;
use App\Model\Dto\Isbn\IsbnDto;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookFinderInfoByIsbn
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /*
     Aixó es correcte PERO, per aplicar correctament arquitectura hexagonal i bones practiques,
     seria recomendable que implementessim una interface HTTP nostra per no dependre de la llibreria de Symfony ja que
     si en un futur symfony cambia de llibreria o nosaltres volem cambiar, estara acoplada al servei ( i a tots els altres llocs on es faci servir ),
     així que ara creem una interface des d'on heredaran els repositoris HTTP. De moment, nomes tindrem el repositori HTTP de SYmfony.
    */

    public function __invoke(string $isbn): IsbnDto
    {
        if ($isbn == null) {
            throw new HttpException(400, "IBSN can not be null!");
        }

        $response = $this->httpClient->request(
            'GET',
            sprintf('https://openlibrary.org/isbn/%s.json', $isbn)
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            throw new HttpException($statusCode, 'Error recuperando el libro');
        }
        $content = $response->getContent();
        $json = json_decode($content, true);

        $isbnDto = new IsbnDto($json['title'], $json['key'], $json['created']['value']);
        return $isbnDto;
    }
}
