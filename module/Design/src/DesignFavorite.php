<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\Mapping as Orm;
use Mandala\UserModule\User;

/**
 * @Orm\Entity(repositoryClass="Mandala\DesignModule\DesignFavoriteRepository")
 */
class DesignFavorite
{
    /**
     * @Orm\Id
     * @Orm\Column(type="integer");
     * @Orm\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Orm\ManyToOne(targetEntity="Mandala\UserModule\User")
     * @var User
     */
    public $user;

    /**
     * @Orm\ManyToOne(targetEntity="Mandala\DesignModule\Design")
     * @var Design
     */
    public $design;
}