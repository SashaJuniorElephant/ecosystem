<?php

namespace App\Service;

use App\Entity\Units\Animal;
use App\Entity\Units\BigPredator;
use App\Entity\Units\Herbivore;
use App\Entity\Units\PlantKingdom;
use App\Entity\Units\PoisonPlant;
use App\Entity\Units\Predator;
use App\Entity\Units\SimplePlant;
use App\Entity\Units\Visitor;
use InvalidArgumentException;

class UnitFactory
{
    /**
     * @param string $name
     * @param int $foodPower
     * @param $type
     * @return PlantKingdom
     */
    public function plant(string $name, int $foodPower, $type): PlantKingdom
    {
        switch ($type) {
            case SimplePlant::class:
                $plant = new SimplePlant();
                break;
            case PoisonPlant::class:
                $plant = new PoisonPlant();
                break;
            default:
                throw new InvalidArgumentException("Неизвестный тип растения");
        }
        $plant->setName($name);
        $plant->setFoodPower($foodPower);

        return $plant;
    }

    /**
     * @param string $name
     * @param int $strength
     * @param $type
     * @return Animal
     */
    public function animal(string $name, int $strength, $type): Animal
    {
        switch ($type) {
            case Herbivore::class:
                $animal = new Herbivore();
                $animal->setStrength($strength);
                break;
            case Predator::class:
                $animal = new Predator();
                $animal->setStrength($strength + 100);
                break;
            case BigPredator::class:
                $animal = new BigPredator();
                $animal->setStrength($strength + 200);
                break;
            default:
                throw new InvalidArgumentException("Передан неизвестный тип животного");
        }
        $animal->setName($name);

        return $animal;
    }

    /**
     * @param string $name
     * @param int $index
     * @return Visitor
     */
    public function visitor(string $name, int $index): Visitor
    {
        $visitor = new Visitor();
        $visitor->setName($name);
        $visitor->setIndex($index);

        return $visitor;
    }
}
