<?php
namespace Mandala\BookModule;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as Orm;
use Mandala\DesignModule\Design;
use Mandala\UserModule\User;

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

        $this->bookDesigns->add($newBookDesign);

        $this->updatePositions();
        $this->designsCount++;
    }

    public function containsDesign(Design $design)
    {
        return $this->findBookDesign($design) !== null;
    }

    public function moveDesign(Design $design, $position)
    {
        $foundBookDesign = $this->findBookDesign($design);
        if ($foundBookDesign === null) {
            throw new \Exception("Design does not exist in book");
        }
//        $this->bookDesigns->removeElement($foundBookDesign);
//        $this->bookDesigns = new ArrayCollection(array(
//            $this->bookDesigns->slice(0, $position),
//            $foundBookDesign,
//            $this->bookDesigns->slice($position)
//        ));
        $this->updatePositions();
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