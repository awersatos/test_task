<?php

namespace App\Controller;

use App\Utils\ApiAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends ApiAbstractController
{
    /**
     * @Route("/book", name="book")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BookController.php',
        ]);
    }
}
