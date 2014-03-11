<?php
namespace Mandala\BookModule;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as Orm;
use Mandala\DesignModule\Design;
use Mandala\UserModule\User;

/**
 * @Orm\Entity
 */
class Book
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLIC = 'public';
    const STATUS_DELETED = 'deleted';

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
    public $author;

    /**
     * @Orm\Column(type="string");
     */
    public $title;

    /**
     * @Orm\OneToMany(targetEntity="Mandala\BookModule\BookDesign", mappedBy="book")
     */
    public $bookDesigns;

    /**
     * @Orm\Column(type="integer");
     */
    public $designsCount = 0;

    public function __construct()
    {
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

        $this->bookDesigns = new ArrayCollection(array(
            $this->bookDesigns->slice(0, $position),
            array($newBookDesign),
            $this->bookDesigns->slice($position)
        ));
        $this->updatePositions();
        $this->designsCount++;
    }

    public function moveDesign(Design $design, $position)
    {
        $foundBookDesign = $this->findBookDesign($design);
        $this->bookDesigns->removeElement($foundBookDesign);
        $this->bookDesigns = new ArrayCollection(array(
            $this->bookDesigns->slice(0, $position),
            $foundBookDesign,
            $this->bookDesigns->slice($position)
        ));
        $this->updatePositions();
    }

    public function removeDesign(Design $design)
    {
        $foundBookDesign = $this->findBookDesign($design);
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
            if ($bookDesign->design = $design) {
                $foundBookDesign = $bookDesign;
            }
        }
        if ($foundBookDesign === null) {
            throw new \Exception("Design does not exist in book");
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