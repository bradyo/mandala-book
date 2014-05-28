<?php
namespace Mandala\BookModule;

use Doctrine\ORM\EntityManager;

class BookManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Book $book)
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function delete(Book $book)
    {
        $book->status = Book::STATUS_DELETED;
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }
} 