<?php

namespace App\Controller\Api;

use App\Service\Isbn\GetBookInfoByIsbn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IsbnController extends AbstractController
{

    public function get(Request $request,GetBookInfoByIsbn $getIsbn): Response
    {
        $isbn = $request->get('isbn', null);
        $data = $getIsbn($isbn);
        return $this->json($data);
    }

    
}
