<?php

namespace App\Service;

use App\Entity\Games;
use App\Entity\MapState;
use App\Entity\Units\BigPredator;
use App\Entity\Units\Herbivore;
use App\Entity\Units\PoisonPlant;
use App\Entity\Units\Predator;
use App\Entity\Units\SimplePlant;
use App\Entity\Units\Visitor;

class MapBuilder
{
    /**
     * @var UnitFactory
     */
    private $unitFactory;

    /**
     * @var MovementService
     */
    private $movementService;

    /**
     * @var HistoryLogsService
     */
    private $historyLogsService;

    public function __construct(
        UnitFactory $unitFactory,
        MovementService $movementService,
        HistoryLogsService $historyLogsService
    )
    {
        $this->unitFactory = $unitFactory;
        $this->movementService = $movementService;
        $this->historyLogsService = $historyLogsService;
    }

    /**
     * @param Games $game
     * @return MapState
     */
    public function newMap(Games $game): MapState
    {
        $map = $this->initMap($game);
        $this->initUnits($game, $map);
        $this->pushHistLog($game, $map);

        return $map;
    }

    /**
     * @param Games $game
     * @return MapState
     */
    private function initMap(Games $game): MapState
    {
        $map = new MapState();
        $game->addMapState($map);

        return $map;
    }

    /**
     * @param Games $game
     * @param MapState $map
     */
    private function initUnits(Games $game, MapState $map): void
    {
        $this->generateUnits($map, SimplePlant::class, $game->getSimplePlants(), 'Растение');
        $this->generateUnits($map, PoisonPlant::class, $game->getPoisonPlants(), 'Ядовитое растение');
        $this->generateUnits($map, Herbivore::class, $game->getHerbivores(), 'Травоядное');
        $this->generateUnits($map, Predator::class, $game->getPredators(), 'Хищник');
        $this->generateUnits($map, BigPredator::class, $game->getBigPredators(), 'Большой хищник');
        $this->generateUnits($map, Visitor::class, $game->getVisitors(), 'Наблюдатель');
    }

    /**
     * @param MapState $map
     * @param $type
     * @param int $amount
     * @param string $title
     */
    private function generateUnits(MapState $map, $type, int $amount, string $title): void
    {
        for ($i = 0; $i < $amount; $i++) {
            $name = $title . ' #' . ($i + 1);
            switch ($type) {
                case SimplePlant::class:
                case PoisonPlant::class:
                    $foodPower = mt_rand(1, 100);
                    $unit = $this->unitFactory->plant($name, $foodPower, $type);
                    break;
                case Herbivore::class:
                case Predator::class:
                case BigPredator::class:
                    $strength = mt_rand(1, 100);
                    $unit = $this->unitFactory->animal($name, $strength, $type);
                    break;
                case Visitor::class:
                    $unit = $this->unitFactory->visitor($name, $i + 1);
                    break;
                default:
                    $unit = null;
            }
            $this->movementService->addUnitRandom($unit, $map);
        }
    }

    private function pushHistLog(Games $game, MapState $map)
    {
        $this->historyLogsService->initMessage($map, $game);
    }
}
