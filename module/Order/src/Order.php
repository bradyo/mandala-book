<?php
namespace Mandala\OrderModule;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as Orm;
use Mandala\DesignModule\Design;
use Mandala\UserModule\User;
use DateTime;

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
     * @Orm\Column(type="datetime")
     * @var DateTime created at time
     */
    public $createdAt;

    /**
     * @Orm\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    public $paidAt;

    /**
     * @Orm\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    public $shippedAt;

    /**
     * @Orm\Column(type="string")
     */
    public $status;

    /**
     * @Orm\Column(type="string", nullable=true)
     * @var string unique hash for this order for customer reference
     */
    public $confirmationCode;

    /**
     * @Orm\ManyToOne(targetEntity="Mandala\UserModule\User")
     * @var User
     */
    public $user;

    /**
     * @Orm\Column(type="string")
     * @var string title of book
     */
    public $title;

    /**
     * @Orm\OneToMany(targetEntity="Mandala\OrderModule\OrderDesign", mappedBy="order", cascade={"persist"})
     */
    public $orderDesigns;

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

    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->orderDesigns = new ArrayCollection();
    }

    public function getDesigns() {
        $designs = array();
        foreach ($this->orderDesigns as $od) {
            $designs[] = $od->design;
        }
        return $designs;
    }

    public function addDesign(Design $design)
    {
        $orderDesign = new OrderDesign();
        $orderDesign->order = $this;
        $orderDesign->design = $design;
        $this->orderDesigns[] = $orderDesign;
    }
}