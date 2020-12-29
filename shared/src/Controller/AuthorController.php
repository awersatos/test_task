<?php

namespace App\Controller;

use App\Entity\Author;
use App\RequestData\AuthorData;
use App\Utils\ApiAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AuthorController extends ApiAbstractController
{
    /**
     * @Route("/author/create", name="author_create", methods={"POST"})
     */
    public function createAction(AuthorData $data): Response
    {
        $author = new Author();
        $author->setName($data->getName());
        $this->em()->persist($author);
        $this->em()->flush();

        return $this->addData('Id', $author->getId())->createResponse();
    }
}
