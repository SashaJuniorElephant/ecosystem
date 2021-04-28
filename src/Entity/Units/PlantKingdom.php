<?php

namespace App\Entity\Units;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitsRepository")
 */
abstract class PlantKingdom extends Units
{
    public function getFoodPower(): int
    {
        return $this->extra;
    }

    public function setFoodPower(int $foodPower): self
    {
        $this->extra = $foodPower;

        return $this;
    }
}
