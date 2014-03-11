<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\Mapping as Orm;
use Mandala\UserModule\User;

/**
 * @Orm\Entity(repositoryClass="Mandala\DesignModule\DesignRepository")
 */
class Design
{
    const STATUS_PUBLIC = 'public';
    const STATUS_DELETED = 'deleted';

    static $shapes = array(
        'circle',
        'triangle',
    );

    /**
     * @Orm\Id
     * @Orm\Column(type="integer")
     * @Orm\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Orm\Column(type="string")
     */
    public $status = self::STATUS_PUBLIC;

    /**
     * @Orm\ManyToOne(targetEntity="Mandala\UserModule\User")
     * @var User
     */
    public $author;

    /**
     * @Orm\Column(type="text")
     * @var string json encoded design data
     */
    public $data;

    /**
     * @Orm\Column(type="integer")
     * @var integer total number of times this design has been favorited
     */
    public $favoritedCount = 0;
}