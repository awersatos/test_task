<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\RequestData\BookData;
use App\Utils\ApiAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Gedmo\Translatable\Entity\Translation;

class BookController extends ApiAbstractController
{
    /**
     * @Route("/book/create", name="book_create", methods={"POST"})
     */
    public function createAction(BookData $data, AuthorRepository $authorRepository): Response
    {
        $author = $authorRepository->find($data->getAuthorId());
        if(!$author) {
            return $this->addErrorMessage('Author not found')->addStatusCode(404)->createResponse();
        }

        $book = new Book();
        $book->setAuthor($author);
        $book->setName($data->getRuName());
        $translation = $this->em()->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $translation->translate($book, 'name', 'en', $data->getEnName());
        $this->em()->persist($book);
        $this->em()->flush();


        return $this->addData('id', $book->getId())->createResponse();
    }

    /**
     * @Route("{_locale<ru|en>}/book/{id}", name="book_get", methods={"GET"})
     */
    public function getAction(Book $book, Request $request)
    {
        $locale = $request->getLocale();
        if($locale !== 'ru') {
            $book->setTranslatableLocale($locale);
            $this->em()->refresh($book);
        }
        return $this->addRootData(['id' => $book->getId(), 'Name' => $book->getName()])->createResponse();
    }
}
