<?php
namespace Mandala\OrderModule;

use Doctrine\ORM\Mapping as Orm;
use Mandala\UserModule\User;
use Mandala\BookModule\Book;

/**
 * @Orm\Entity
 * @Orm\Table(name="`Order`")
 */
class Order
{
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_SHIPPED = 'shipped';

    const DELIVERY_TYPE_EMAIL = 'email';
    const DELIVERY_TYPE_MAIL = 'mail';

    /**
     * @Orm\Id
     * @Orm\Column(type="integer");
     * @Orm\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Orm\Column(type="string")
     */
    public $status;

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

    /**
     * @Orm\Column(type="string")
     */
    public $deliveryMethod;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    public $recipientEmail;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    public $shippingName;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    public $shippingStreet;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    public $shippingCity;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    public $shippingState;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    public $shippingZip;

    /**
     * @Orm\Column(type="decimal", precision=6, scale=2)
     */
    public $goodsCost;

    /**
     * @Orm\Column(type="decimal", precision=6, scale=2)
     */
    public $taxCost;

    /**
     * @Orm\Column(type="decimal", precision=6, scale=2)
     */
    public $shippingCost;

    /**
     * @Orm\Column(type="decimal", precision=6, scale=2)
     */
    public $totalCost;

    /**
     * @return string unique hash for this order for customer reference
     */
    public function getConfirmationCode()
    {
        return strtoupper(substr(sha1($this->id), 0, 10));
    }
}