<?php
namespace Mandala\OrderModule;

use Doctrine\ORM\Mapping as Orm;
use Mandala\DesignModule\Design;

/**
 * @Orm\Entity
 */
class OrderDesign
{
    /**
     * @Orm\Id
     * @Orm\Column(type="integer");
     * @Orm\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Orm\ManyToOne(targetEntity="Mandala\OrderModule\Order", inversedBy="orderDesigns", cascade={"persist"})
     * @var Order
     */
    public $order;

    /**
     * @Orm\ManyToOne(targetEntity="Mandala\DesignModule\Design")
     * @var Design
     */
    public $design;
}