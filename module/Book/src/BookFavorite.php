<?php
namespace Mandala\BookModule;

use Doctrine\ORM\Mapping as Orm;
use Mandala\UserModule\User;

/**
 * @Orm\Entity(repositoryClass="Mandala\BookModule\BookFavoriteRepository")
 */
class BookFavorite
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
     * @Orm\ManyToOne(targetEntity="Mandala\BookModule\Book")
     * @var Book
     */
    public $book;
}