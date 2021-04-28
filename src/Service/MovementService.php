<?php

namespace App\Service;

use App\Entity\MapState;
use App\Entity\Points;
use App\Entity\SidesOfWorld;
use App\Entity\Units\Animal;
use App\Entity\Units\AnimalKingdom;
use App\Entity\Units\PlantKingdom;
use App\Entity\Units\Units;
use App\Entity\Units\Visitor;
use InvalidArgumentException;

class MovementService
{
    /**
     * @var HistoryLogsService
     */
    private $historyLogsService;

    /**
     * @var VisitorLogsService
     */
    private $visitorLogsService;

    public function __construct(HistoryLogsService $historyLogsService, VisitorLogsService $visitorLogsService)
    {
        $this->historyLogsService = $historyLogsService;
        $this->visitorLogsService = $visitorLogsService;
    }

    public function addUnit(Units $unit, MapState $map, Points $point): void
    {
        $point->addUnit($unit);
        $map->addUnit($unit);
    }

    public function deleteUnit(Units $unit): void
    {
        $point = $unit->getPoint();
        $point->removeUnit($unit);

        $map = $unit->getMapState();
        $map->removeUnit($unit);
    }

    private function getRandPoint(MapState $map): Points
    {
        $points = $map->getGame()->getPoints()->toArray();
        return $points[array_rand($points)];
    }

    public function addUnitRandom(Units $unit, MapState $map): void
    {
        $randPoint = $this->getRandPoint($map);
        $this->addUnit($unit, $map, $randPoint);
    }

    public function putUnit(Units $unit, Points $pointTo): void
    {
        $pointTo->addUnit($unit);
    }

    private function takeUnit(Units $unit): void
    {
        $unit->getPoint()->removeUnit($unit);
    }

    private function moveAnimalKingdomUnit(AnimalKingdom $unit, Points $pointTo): void
    {
        $this->logUnitMovement($unit, $pointTo);
        $this->takeUnit($unit);
        $this->putUnit($unit, $pointTo);
    }

    private function movePlantKingdomUnit(PlantKingdom $unit): void
    {
        $this->takeUnit($unit);
        $randPoint = $this->getRandPoint($unit->getMapState());
        $this->putUnit($unit, $randPoint);
        $this->logUnitMovement($unit);
    }

    private function logUnitMovement(Units $unit, Points $pointTo = null): void
    {
        if ($unit instanceof AnimalKingdom) {
            $this->historyLogsService->moveUnitMessage($unit, $pointTo);

            if ($unit instanceof Visitor) {
                $this->visitorLogsService->moveUnitMessage($unit, $pointTo);
            }
        } elseif ($unit instanceof PlantKingdom) {
            $this->historyLogsService->moveGrassMessage($unit);
        }
    }

    public function makeAutoStep(Units $unit): void
    {
        if ($unit instanceof Animal) {
            $this->stepOfAnimal($unit);
        } elseif ($unit instanceof Visitor) {
            $this->stepOfVisitor($unit);
        } elseif ($unit instanceof PlantKingdom) {
            $this->movePlantKingdomUnit($unit);
        } else {
            throw new InvalidArgumentException("Для этого юнита логика передвижения не реализована");
        }
    }

    private function stepOfAnimal(Animal $unit): void
    {
        $direction = $this->chooseCorrectDirection($unit, false);
        $endPoint = $this->calculateEndPoint($unit, $direction);
        $this->moveAnimalKingdomUnit($unit, $endPoint);
    }

    private function stepOfVisitor(Visitor $unit): void
    {
        $direction = $this->chooseCorrectDirection($unit, true);
        $amountSteps = mt_rand(1, $unit->getMapState()->getGame()->getDimension() - 1); // количество шагов наблюдателя
        $endPoint = $this->calculateEndPoint($unit, $direction, $amountSteps);
        $this->moveAnimalKingdomUnit($unit, $endPoint);
    }

    /**
     * @param AnimalKingdom $unit
     * @param bool $transparent , "прозрачные границы поля"
     * @return int
     */
    private function chooseCorrectDirection(AnimalKingdom $unit, bool $transparent): int
    {
        $mask = 0;
        $point = $unit->getPoint();
        $dimension = $unit->getMapState()->getGame()->getDimension();

        if (!$transparent) {
            if ($point->getX() == 1) {
                $mask |= SidesOfWorld::WEST;
            }
            if ($point->getX() == $dimension) {
                $mask |= SidesOfWorld::EAST;
            }
            if ($point->getY() == 1) {
                $mask |= SidesOfWorld::NORTH;
            }
            if ($point->getY() == $dimension) {
                $mask |= SidesOfWorld::SOUTH;
            }
        }
        $sides = SidesOfWorld::getArray($mask);
        return $sides[array_rand($sides)];
    }

    private function calculateEndPoint(AnimalKingdom $unit, int $direction, int $amountSteps = 1)
    {
        $game = $unit->getMapState()->getGame();
        $dimension = $game->getDimension();
        $startPoint = $unit->getPoint();
        $x = $startPoint->getX();
        $y = $startPoint->getY();

        switch ($direction) {
            case SidesOfWorld::NORTH:
                $y = $y + $dimension - $amountSteps;
                $y = $this->calculateModuloPoint($y, $dimension);
                break;
            case SidesOfWorld::EAST:
                $x = $x + $amountSteps;
                $x = $this->calculateModuloPoint($x, $dimension);
                break;
            case SidesOfWorld::SOUTH:
                $y = $y + $amountSteps;
                $y = $this->calculateModuloPoint($y, $dimension);
                break;
            case SidesOfWorld::WEST:
                $x = $x + $dimension - $amountSteps;
                $x = $this->calculateModuloPoint($x, $dimension);
                break;
            default:
                throw new InvalidArgumentException("Неизвестное направление движения");
        }

        return $game->getPointByCoords($x, $y);
    }

    private function calculateModuloPoint($coordinate, $dimension)
    {
        return $coordinate <= $dimension ? $coordinate : $coordinate % $dimension;
    }
}
