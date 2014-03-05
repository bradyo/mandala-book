<?php
namespace Mandala\DesignModule;

use Doctrine\ORM\Mapping as Orm;

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
     * @Orm\ManyToOne(targetEntity="Book")
     * @var Book
     */
    public $book;

    /**
     * @Orm\ManyToOne(targetEntity="Design")
     * @var Design
     */
    public $design;

    /**
     * @Orm\Column(type="integer")
     */
    public $position = 0;
}