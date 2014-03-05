<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\EntityRepository;
use Mandala\UserModule\User;

class DesignFavoriteRepository extends EntityRepository
{
    public function isFavorite(User $user, Design $design)
    {
        $criteria = array(
            'user' => $user,
            'design' => $design
        );
        $favorite = $this->findOneBy($criteria);
        return $favorite !== null;
    }
} 