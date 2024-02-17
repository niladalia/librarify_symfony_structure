<?php

namespace App\Service\Utils;

use App\Service\Interfaces\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GuzzleClient
{#implements HttpClientInterface{
    public function __construct() {}

    public function request(string $method, string $url, ?array $options = []) {}
}
