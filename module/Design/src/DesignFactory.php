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

    public function createScreenSaverDesign()
    {
        $shapes = array(
            'triangle',
            'star',
            'square',
            'diamond',
            'teardrop',
            'heart',
        );

        $design = new Design();
        $design->data = json_encode(array(
            array(
                "shapeType" => $this->getRandomShapesIn($shapes),
                "shapeSize" => rand(50, 180),
                "shapeCount" => rand(5, 15),
                "displacement" => rand(30, 300),
                "angleOffset" => 0,
                "rotation" => 0,
            )
        ));
        return $design;
    }

    public function createFancyDesign()
    {
        $design = new Design();

        $layer = array(
            "shapeType" => $this->getRandomShapeType(),
            "shapeSize" => rand(10, 180),
            "shapeCount" => rand(3, 9),
            "displacement" => rand(1, 80),
            "angleOffset" => 0,
            "rotation" => 0,
        );

        $layer2 = array(
            "shapeType" => $this->getRandomShapeType(),
            "shapeSize" => rand(10, 100),
            "shapeCount" => $layer['shapeCount'] * 2,
            "displacement" => rand(50, 120),
            "angleOffset" => 0,
            "rotation" => 0,
        );

        $layers = array();

        $count = rand(1,5);
        $d2 = rand(10, 50);
        $delta = 10 * rand(1,5);
        for ($i = 0; $i < $count; $i++) {
            $layers[] = $this->modulateDisplacement($this->modulateSize($layer, $i * $d2), $i * $delta);
        }

        $count = rand(1, 3);
        $d2 = rand(40, 80);
        $delta = 1.5* $delta;
        $deltax = 10 + rand(0, 40);
        for ($i = 0; $i < $count; $i++) {
            $layers[] = $this->modulateDisplacement($this->modulateSize($layer2, $i * $d2), $i * $delta + $deltax);
        }

        $design->data = json_encode($layers);

        return $design;
    }

    private function modulateSize($layer, $delta) {
        $layer['shapeSize'] += $delta;
        return $layer;
    }

    private function modulateDisplacement($layer, $delta) {
        $layer['displacement'] += $delta;
        return $layer;
    }

    private function getRandomShapeType()
    {
        return Design::$shapes[rand(0, count(Design::$shapes) - 1)];
    }

    private function getRandomShapesIn(array $types)
    {
        return $types[rand(0, count($types) - 1)];
    }
} 