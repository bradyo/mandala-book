<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Mandala\UserModule\User;

class DesignFavoriteManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(User $user, Design $design)
    {
        $criteria = array(
            'user' => $user,
            'design' => $design
        );
        ;
        $favorite = $this->getDesignFavoriteRepository()->findOneBy($criteria);
        if ($favorite == null) {
            $favorite = new DesignFavorite();
            $favorite->user = $user;
            $favorite->design = $design;
            $this->entityManager->persist($favorite);

            // update design favorite count
            $design->favoritedCount++;
            $this->entityManager->persist($design);
            $this->entityManager->flush();
        }
    }

    public function remove(User $user, Design $design)
    {
        $criteria = array(
            'user' => $user,
            'design' => $design
        );
        $favorite = $this->getDesignFavoriteRepository()->findOneBy($criteria);
        if ($favorite !== null) {
            $this->entityManager->remove($favorite);

            // update design count
            $design->favoritedCount--;
            $this->entityManager->persist($design);
            $this->entityManager->flush();
        }
    }

    /**
     * @return EntityRepository
     */
    private function getDesignFavoriteRepository()
    {
        return $this->entityManager->getRepository('Mandala\DesignModule\DesignFavorite');
    }
}