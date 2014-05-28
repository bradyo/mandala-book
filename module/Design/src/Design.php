<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\Mapping as Orm;
use Mandala\UserModule\User;
use DateTime;

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
        'star',
        'heart',
        'square',
        'diamond',
        'leaf'
    );

    /**
     * @Orm\Id
     * @Orm\Column(type="integer")
     * @Orm\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Orm\Column(type="datetime")
     * @var DateTime created at time
     */
    public $createdAt;

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


    public function __construct()
    {
        $this->createdAt = new DateTime("now");
    }

    public function toJson()
    {
        $data = array(
            'id' => $this->id,
            'createdAt' => $this->createdAt->format(DateTime::ISO8601),
            'layers' => json_decode($this->data, true),
        );

        return json_encode($data);
    }
}