<?php
namespace Mandala\BookModule;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as Orm;
use Mandala\DesignModule\Design;
use Mandala\UserModule\User;
use DateTime;

/**
 * @Orm\Entity(repositoryClass="Mandala\BookModule\BookRepository")
 */
class Book
{
    const STATUS_PUBLIC = 'public';
    const STATUS_DELETED = 'deleted';

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
     * @Orm\Column(type="string")
     */
    public $status = self::STATUS_PUBLIC;

    /**
     * @Orm\ManyToOne(targetEntity="Mandala\UserModule\User")
     * @var User
     */
    public $author;

    /**
     * @Orm\Column(type="string");
     */
    public $title;

    /**
     * @Orm\OneToMany(targetEntity="Mandala\BookModule\BookDesign", mappedBy="book", cascade={"persist"})
     */
    public $bookDesigns;

    /**
     * @Orm\Column(type="integer");
     */
    public $designsCount = 0;

    /**
     * @Orm\Column(type="integer")
     * @var integer total number of times this book has been favorited
     */
    public $favoritedCount = 0;


    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->bookDesigns = new ArrayCollection();
    }

    public function addDesign(Design $design, $position = null)
    {
        if ($position === null) {
            $position = $this->designsCount;
        }
        $newBookDesign = new BookDesign();
        $newBookDesign->design = $design;
        $newBookDesign->book = $this;
        $newBookDesign->position = $position;

        $this->bookDesigns->add($newBookDesign);

        $this->updatePositions();
        $this->designsCount++;
    }

    public function containsDesign(Design $design)
    {
        return $this->findBookDesign($design) !== null;
    }

    public function removeDesign(Design $design)
    {
        $foundBookDesign = $this->findBookDesign($design);
        if ($foundBookDesign === null) {
            throw new \Exception("Design does not exist in book");
        }
        $this->bookDesigns->removeElement($foundBookDesign);
        $this->updatePositions();
        $this->designsCount--;
    }

    /**
     * @param Design $design
     * @return BookDesign
     * @throws \Exception
     */
    private function findBookDesign(Design $design)
    {
        $foundBookDesign = null;
        foreach ($this->bookDesigns as $bookDesign) {
            if ($bookDesign->design == $design) {
                $foundBookDesign = $bookDesign;
            }
        }
        return $foundBookDesign;
    }

    private function updatePositions()
    {
        $position = 0;
        foreach ($this->bookDesigns as $bookDesign) {
            $bookDesign->position = $position;
            $position++;
        }
    }
}