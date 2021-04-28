<?php

namespace App\Entity\Units;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitsRepository")
 */
abstract class Animal extends AnimalKingdom
{
    public function getStrength(): int
    {
        return $this->extra;
    }

    public function setStrength(int $strength): void
    {
        $this->extra = $strength;
    }
}
