<?php
namespace Mandala\BookModule;

use Doctrine\ORM\EntityManager;

class BookManager
{
    private $entityManager;
    private $fileService;

    public function __construct(EntityManager $entityManager, BookFileService $fileService)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
    }

    public function save(Book $book)
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->fileService->generatePdf($book);
    }

    public function delete(Book $book)
    {
        $book->status = Book::STATUS_DELETED;
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }
} 