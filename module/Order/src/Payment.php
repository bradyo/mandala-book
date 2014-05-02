<?php
namespace Mandala\OrderModule;

use Doctrine\ORM\Mapping as Orm;

/**
 */
class Payment
{
    /**
     * @Orm\Id
     * @Orm\Column(type="integer");
     * @Orm\GeneratedValue(strategy="AUTO")
     */
    public $id;
}