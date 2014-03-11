<?php
namespace Mandala\BookModule;

use Doctrine\ORM\Mapping as Orm;
use Mandala\DesignModule\Design;

/**
 * @Orm\Entity
 */
class BookDesign
{
    /**
     * @Orm\Id
     * @Orm\Column(type="integer");
     * @Orm\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Orm\ManyToOne(targetEntity="Mandala\BookModule\Book", inversedBy="bookDesigns")
     * @var Book
     */
    public $book;

    /**
     * @Orm\ManyToOne(targetEntity="Mandala\DesignModule\Design")
     * @var Design
     */
    public $design;

    /**
     * @Orm\Column(type="integer")
     */
    public $position = 0;
}