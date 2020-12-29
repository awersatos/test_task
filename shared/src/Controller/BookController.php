<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
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
        if (!$author) {
            return $this->addErrorMessage('Author not found')->addStatusCode(404)->createResponse();
        }

        $book = new Book();
        $book->setAuthor($author);
        $book->setName($data->getRuName());
        $translation = $this->em()->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $translation->translate($book, 'name', 'en', $data->getEnName());
        $this->em()->persist($book);
        $this->em()->flush();


        return $this->addData('Id', $book->getId())->createResponse();
    }

    /**
     * @Route("{_locale<ru|en>}/book/{id}", name="book_get", methods={"GET"})
     */
    public function getAction(Book $book, Request $request): Response
    {
        $locale = $request->getLocale();
        if ($locale !== 'ru') {
            $book->setTranslatableLocale($locale);
            $this->em()->refresh($book);
        }

        return $this->addRootData([
            'Id' => $book->getId(),
            'Name' => $book->getName(),
        ])->createResponse();
    }

    /**
     * @Route("/book/search/{needle}", name="book_search", methods={"GET"})
     */
    public function searchAction(string $needle, BookRepository $bookRepository): Response
    {
        if (mb_strlen($needle) < 3) {
            return $this->addErrorMessage('Search string is too shoot')->createResponse();
        }

        $books = $bookRepository->searchByName($needle);
        $result = [];

        foreach ($books as $book) {
            $result[]  = [
                'Id' => $book->getId(),
                'Name' => $book->getName(),
                'Author' => [
                    'Id' => $book->getAuthor()->getId(),
                    'Name' => $book->getAuthor()->getName()
                ]
            ];
        }
        return $this->addRootData($result)->createResponse();
    }

}
