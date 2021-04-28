<?php

namespace App\Service;

use App\Entity\Games;
use App\Entity\MapState;
use App\Repository\MapStateRepository;
use Doctrine\ORM\EntityManagerInterface;

class MapService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MapStateRepository
     */
    private $repository;

    /**
     * @var MovementService
     */
    private $movementService;

    public function __construct(
        EntityManagerInterface $em,
        MapStateRepository $repository,
        MovementService $movementService
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->movementService = $movementService;
    }

    public function getLastMapState(Games $game): MapState
    {
        return $game->isUseSessions() ? $this->chooseLastMapState($game) : $this->loadLastMapState($game);
    }

    private function loadLastMapState(Games $game): MapState
    {
        $map = $this->repository->findLastStateByGameID($game->getId());
        $this->putUnitsOnMap($map);

        return $map;
    }

    private function putUnitsOnMap(MapState $map): void
    {
        $map->getGame()->clearPoints();
        foreach ($map->getUnits() as $unit) {
            $this->movementService->putUnit($unit, $unit->getPoint());
        }
    }

    private function chooseLastMapState(Games $game): MapState
    {
        $step = 0;
        $map = null;
        foreach ($game->getMapStates() as $mapState){
            $mapStep = $mapState->getStep();
            if ($mapStep >= $step){
                $step = $mapStep;
                $map = $mapState;
            }
        }

        return $map;
    }

    public function getNextStepMapState(MapState $map): MapState
    {
        $newMap = $this->getEmptyMap($map);
        $this->addNewMap($map->getGame(), $newMap);
        $this->cloneUnits($newMap, $map);

        return $newMap;
    }

    private function getEmptyMap(MapState $map): MapState
    {
        return new MapState($this->calcNextStep($map));
    }

    private function calcNextStep(MapState $map): int
    {
        $step = $map->getStep();

        return ++$step;
    }

    private function addNewMap(Games $game, MapState $newMap): void
    {
        $game->addMapState($newMap);
        $game->clearPoints();
    }

    private function cloneUnits(MapState $newMap, MapState $oldMap): void
    {
        foreach ($oldMap->getUnits() as $unit) {
            $clonedUnit = clone $unit;
            $this->movementService->addUnit($clonedUnit, $newMap, $unit->getPoint());
        }
    }
}
