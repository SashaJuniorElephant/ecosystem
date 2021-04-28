<?php

namespace App\Entity\Units;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitsRepository")
 */
abstract class AnimalKingdom extends Units
{
    /**
     * @var bool
     */
    private $isEaten = false;

    /**
     * @return bool
     */
    public function isEaten(): bool
    {
        return $this->isEaten;
    }

    public function setIsEaten(): void
    {
        $this->isEaten = true;
    }
}
