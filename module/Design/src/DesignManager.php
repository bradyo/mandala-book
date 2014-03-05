<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\EntityManager;
use Mandala\UserModule\User;

class DesignManager
{
    private $entityManager;
    private $renderer;
    private $svgOutputPath;

    public function __construct(EntityManager $entityManager, DesignRenderer $renderer, $svgOutputPath)
    {
        $this->entityManager = $entityManager;
        $this->renderer = $renderer;
        $this->svgOutputPath = $svgOutputPath;
    }

    public function save(User $author, array $data)
    {
        $design = Design::createDefault($author);
        $this->persist($design, $data);
    }

    public function update(Design $design, array $data)
    {
        $this->persist($design, $data);
    }

    private function persist(Design $design, array $data)
    {
        $design->data = $data['data'];
        $design->svg =  '<?xml version="1.0" encoding="utf-8"?>' . $this->renderer->render($design);
        $this->entityManager->persist($design);
        $this->entityManager->flush();

        // save svg content to file
        file_put_contents($this->svgOutputPath . '/' . $design->id . '.svg', $design->svg);
    }

    public function delete(Design $design)
    {
        $design->status = Design::STATUS_DELETED;
        $this->entityManager->persist($design);
        $this->entityManager->flush();
    }
} 