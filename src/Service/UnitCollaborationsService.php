<?php

namespace App\Service;

use App\Entity\Units\Animal;
use App\Entity\Units\BigPredator;
use App\Entity\Units\Herbivore;
use App\Entity\Units\PlantKingdom;
use App\Entity\Units\PoisonPlant;
use App\Entity\Units\Predator;
use App\Entity\Units\SimplePlant;
use App\Entity\Units\Units;
use App\Entity\Units\Visitor;
use InvalidArgumentException;

class UnitCollaborationsService
{
    /**
     * @var VisitorLogsService
     */
    private $visitorLogsService;

    /**
     * @var MovementService
     */
    private $movementService;

    /**
     * @var HistoryLogsService
     */
    private $historyLogsService;

    public function __construct(
        MovementService $movementService,
        HistoryLogsService $historyLogsService,
        VisitorLogsService $visitorLogsService
    )
    {
        $this->movementService = $movementService;
        $this->historyLogsService = $historyLogsService;
        $this->visitorLogsService = $visitorLogsService;
    }

    /**
     * @param Units $mainUnit
     */
    public function entrance(Units $mainUnit): void
    {
        if ($mainUnit instanceof Visitor) {
            $this->visitorDo($mainUnit);
        } elseif
        ($mainUnit instanceof Herbivore) {
            $this->herbivoreDo($mainUnit);
        } elseif ($mainUnit instanceof BigPredator) {
            $this->bigPredatorDo($mainUnit);
        } elseif ($mainUnit instanceof Predator) {
            $this->predatorDo($mainUnit);
        } else {
            throw new InvalidArgumentException("Для этого юнита логика взаимодействий не реализована");
        }
    }

    private function visitorDo(Visitor $mainUnit): void
    {
        $point = $mainUnit->getPoint();

        if ($point->countUnits() == 1) { // Если в этой точке находится только 1 юнит (то есть сам наблюдатель)
            $this->visitorLogsService->emptyPointMessage($mainUnit);
        } else {
            foreach ($point->getUnits() as $unit) {
                if ($unit === $mainUnit) {
                    continue;
                }
                if ($unit instanceof PlantKingdom) {
                    $this->visitorLogsService->collectPlantsMessage($mainUnit, $unit);
                    $this->movementService->makeAutoStep($unit);
                } elseif ($unit instanceof Animal) {
                    $this->visitorLogsService->meetAnimalMessage($mainUnit, $unit);
                } elseif ($unit instanceof Visitor) {
                    $this->visitorLogsService->meetVisitorMessage($mainUnit, $unit);
                } else {
                    $this->visitorLogsService->unknownUnitMessage($mainUnit);
                    throw new InvalidArgumentException("Неизвестный юнит");
                }
            }
        }
    }

    private function herbivoreDo(Herbivore $mainUnit): void
    {
        $units = $this->filterUnits($mainUnit);
        $predators = array_merge($units['predators'], $units['bigPredators']);
        $this->comeToHigherLevelAnimals($mainUnit, $predators);
        $this->comeToSimplePlants($mainUnit, $units['simplePlants']);
    }

    private function predatorDo(Predator $mainUnit): void
    {
        $units = $this->filterUnits($mainUnit);
        $this->comeToHigherLevelAnimals($mainUnit, $units['bigPredators']);
        $this->comeToEqualsLevelAnimals($mainUnit, $units['predators']);
        $this->comeToLowerLevelAnimals($mainUnit, $units['herbivores']);
    }

    private function bigPredatorDo(BigPredator $mainUnit): void
    {
        $units = $this->filterUnits($mainUnit);
        $lowerAnimals = array_merge($units['predators'], $units['herbivores']);
        $this->comeToEqualsLevelAnimals($mainUnit, $units['bigPredators']);
        $this->comeToLowerLevelAnimals($mainUnit, $lowerAnimals);
    }

    private function filterUnits(Units $mainUnit): array
    {
        $point = $mainUnit->getPoint();
        $units = [
            'simplePlants' => [],
            'poisonPlants' => [],
            'herbivores'   => [],
            'bigPredators' => [],
            'predators'    => [],
            'visitors'     => [],
            'otherUnits'   => [],
        ];

        foreach ($point->getUnits() as $unit) {
            if ($mainUnit === $unit) {
                continue;
            }
            if ($unit instanceof SimplePlant) {
                $units['simplePlants'][] = $unit;
            } elseif ($unit instanceof PoisonPlant) {
                $units['poisonPlants'][] = $unit;
            } elseif ($unit instanceof Herbivore) {
                $units['herbivores'][] = $unit;
            } elseif ($unit instanceof BigPredator) {
                $units['bigPredators'][] = $unit;
            } elseif ($unit instanceof Predator) {
                $units['predators'][] = $unit;
            } elseif ($unit instanceof Visitor) {
                $units['visitors'][] = $unit;
            } else {
                $units['otherUnits'][] = $unit;
            }
        }

        return $units;
    }

    private function isStronger(Animal $walker, Animal $defender): bool
    {
        return $walker->getStrength() >= $defender->getStrength();
    }

    private function hurt(Animal $stronger, Animal $weaker): void
    {
        $this->historyLogsService->hurtMessage($stronger, $weaker);
        $weaker->setStrength($weaker->getStrength() - $stronger->getStrength());
    }

    private function killAndEat(Animal $stronger, Animal $weaker): void
    {
        $this->historyLogsService->killAndEatMessage($stronger, $weaker);
        $stronger->setStrength($stronger->getStrength() + $weaker->getStrength());
        $this->movementService->deleteUnit($weaker);
        $weaker->setIsEaten();
    }

    private function comeToLowerLevelAnimals(Animal $mainUnit, array $otherAnimals): void
    {
        if ($mainUnit->isEaten()) {
            return;
        }

        foreach ($otherAnimals as $otherAnimal) {
            if ($this->isStronger($mainUnit, $otherAnimal)) {
                $this->killAndEat($mainUnit, $otherAnimal);
            } else {
                $this->hurt($mainUnit, $otherAnimal);
            }
        }
    }

    private function comeToEqualsLevelAnimals(Animal $mainUnit, array $otherAnimals): void
    {
        if ($mainUnit->isEaten()) {
            return;
        }

        foreach ($otherAnimals as $otherAnimal) {
            if ($this->isStronger($mainUnit, $otherAnimal)) {
                $this->killAndEat($mainUnit, $otherAnimal);
            } else {
                $this->killAndEat($otherAnimal, $mainUnit);
                break;
            }
        }
    }

    private function comeToHigherLevelAnimals(Animal $mainUnit, array $otherAnimals): void
    {
        if ($mainUnit->isEaten()) {
            return;
        }

        foreach ($otherAnimals as $otherAnimal) {
            if ($this->isStronger($mainUnit, $otherAnimal)) {
                $this->hurt($otherAnimal, $mainUnit);
            } else {
                $this->killAndEat($otherAnimal, $mainUnit);
                break;
            }
        }
    }

    private function comeToSimplePlants(Animal $mainUnit, array $plants): void
    {
        if ($mainUnit->isEaten()) {
            return;
        }

        /** @var PlantKingdom $plant */
        foreach ($plants as $plant) {
            $this->historyLogsService->eatPlantMessage($mainUnit, $plant);
            $mainUnit->setStrength($plant->getFoodPower() + $mainUnit->getStrength());
            $this->movementService->makeAutoStep($plant);
        }
    }
}
