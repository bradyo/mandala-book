<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\Mapping as Orm;
use Mandala\UserModule\User;

/**
 * @Orm\Entity(repositoryClass="Mandala\DesignModule\DesignRepository")
 */
class Design
{
    const STATUS_SAVED = 'saved';
    const STATUS_DELETED = 'deleted';

    static $shapes = array(
        'circle',
        'triangle',
    );

    public static function createDefault(User $author)
    {
        $design = new self();
        $design->author = $author;
        $design->data = json_encode(array(
            array(
                "shapeType" => 'circle',
                "shapeSize" => 20,
                "shapeCount" => 3,
                "displacement" => 100,
                "angleOffset" => 0,
                "rotation" => 0
            )
        ));
        return $design;
    }

    public static function createRandom(User $author)
    {
        $design = new self();
        $design->author = $author;
        $design->data = json_encode(array(
            array(
                "shapeType" => self::$shapes[rand(0, count(self::$shapes) - 1)],
                "shapeSize" => rand(10, 180),
                "shapeCount" => rand(2, 20),
                "displacement" => rand(10, 300),
                "angleOffset" => rand(0, 0),
                "rotation" => rand(0, 360),
            )
        ));
        return $design;
    }

    /**
     * @Orm\Id
     * @Orm\Column(type="integer")
     * @Orm\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Orm\Column(type="string")
     */
    public $status = self::STATUS_SAVED;

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
     * @Orm\Column(type="text", nullable=true)
     * @var string svg xml data
     */
    public $svg;

    /**
     * @Orm\Column(type="integer")
     * @var integer total number of times this design has been favorited
     */
    public $favoritedCount = 0;
}