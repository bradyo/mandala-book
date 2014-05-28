<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\EntityManager;
use Mandala\Analytics\Tracking\Event;
use Mandala\Analytics\Tracking\Tracker;
use Mandala\UserModule\User;

class DesignManager
{
    private $entityManager;
    private $fileService;
    private $tracker;

    public function __construct(EntityManager $entityManager, DesignFileService $fileService, Tracker $tracker)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
        $this->tracker = $tracker;
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

        $this->tracker->log(new Event(Event::NEW_DESIGN));

        return $design;
    }

    public function delete(Design $design)
    {
        $design->status = Design::STATUS_DELETED;
        $this->entityManager->persist($design);
        $this->entityManager->flush();
    }
} 