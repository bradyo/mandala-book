<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\Mapping as Orm;
use Mandala\UserModule\User;

/**
 * @Orm\Entity
 */
class Book
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
    public $author;

    /**
     * @Orm\Column(type="string");
     */
    public $title;
}