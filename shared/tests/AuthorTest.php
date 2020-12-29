<?php

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\AuthorRepository;

class AuthorTest extends KernelTestCase
{
    private EntityManager $em;
    private AuthorRepository $authorRepository;
    private string $name;

    protected function setUp()
    {
        self::bootKernel();
        $container = self::$container;
        $this->em = $container->get('doctrine')->getManager();
        $this->authorRepository = $this->em->getRepository(Author::class);
        $timestamp = (new \DateTime())->getTimestamp();
        $this->name = 'Тестовый Автор ' . $timestamp;
    }


    public function testCreateAndFind()
    {
        $author = new Author();
        $author->setName($this->name);
        $this->em->persist($author);
        $this->em->flush();

        $author = $this->authorRepository->findOneBy(['name' => $this->name]);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals($this->name, $author->getName());
    }
}
