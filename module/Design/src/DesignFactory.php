<?php
namespace Mandala\DesignModule;

use Mandala\UserModule\User;

class DesignFactory 
{
    public function createDefault(User $author)
    {
        $design = new Design();
        $design->author = $author;
        $design->data = json_encode(array(
            array(
                "shapeType" => 'circle',
                "shapeSize" => 20,
                "shapeCount" => 3,
                "displacement" => 100,
                "angleOffset" => 0,
                "rotation" => 0
            )
        ));
        return $design;
    }

    public function createRandom(User $author)
    {
        $design = new Design();
        $design->author = $author;
        $design->data = json_encode(array(
            array(
                "shapeType" => $this->getRandomShapeType(),
                "shapeSize" => rand(10, 180),
                "shapeCount" => rand(2, 20),
                "displacement" => rand(10, 300),
                "angleOffset" => 0,
                "rotation" => 0,
            )
        ));
        return $design;
    }

    private function getRandomShapeType()
    {
        return Design::$shapes[rand(0, count(Design::$shapes) - 1)];
    }
} 