<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\EntityManager;
use Mandala\UserModule\User;

class DesignManager
{
    private $entityManager;
    private $fileService;

    public function __construct(EntityManager $entityManager, DesignFileService $fileService)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
    }

    public function save(User $author, $data)
    {
        $design = new Design();
        $design->author = $author;
        $design->data = $data;
        $this->entityManager->persist($design);
        $this->entityManager->flush();

        $this->fileService->createSvg($design);
        $this->fileService->createThumbnail($design, 164);
    }

    public function delete(Design $design)
    {
        $design->status = Design::STATUS_DELETED;
        $this->entityManager->persist($design);
        $this->entityManager->flush();
    }
} 