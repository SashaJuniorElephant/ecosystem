<?php

namespace App\Service;

use App\Entity\Games;
use App\Entity\HistoryLogs;
use App\Entity\MapState;
use App\Entity\Points;
use App\Entity\Units\Animal;
use App\Entity\Units\PlantKingdom;
use App\Entity\Units\SimplePlant;
use App\Entity\Units\Units;

class HistoryLogsService
{
    private function newMessage(MapState $map, string $message): void
    {
        $histLog = new HistoryLogs();
        $histLog->setMessage($message);
        $map->addHistoryLog($histLog);
    }

    public function initMessage(MapState $map, Games $game): void
    {
        $this->newMessage($map, 'Название: ' . $game->getName());
        $this->newMessage($map, 'Размер: ' . $game->getDimension());
        $this->newMessage($map, 'Количество наблюдений: ' . $game->getSteps());
        $this->newMessage($map, 'Количество растений: ' . $game->getSimplePlants());
        $this->newMessage($map, 'Количество ядовитых растений: ' . $game->getPoisonPlants());
        $this->newMessage($map, 'Количество травоядных: ' . $game->getHerbivores());
        $this->newMessage($map, 'Количество хищников: ' . $game->getPredators());
        $this->newMessage($map, 'Количество больших хищников: ' . $game->getBigPredators());
        $this->newMessage($map, 'Количество наблюдателей: ' . $game->getVisitors());
    }

    public function moveUnitMessage(Units $unit, Points $pointTo): void
    {
        $this->newMessage($unit->getMapState(),
            $unit->getName()
            . ' пришел из (' . $unit->getPoint()->getX() . ', ' . $unit->getPoint()->getY() . ')'
            . ' в точку (' . $pointTo->getX() . ', ' . $pointTo->getY() . ')'
        );
    }

    public function moveGrassMessage(PlantKingdom $unit): void
    {
        $this->newMessage($unit->getMapState(),
            'Новое ' . $unit->getName()
            . ' выросло в точке (' . $unit->getPoint()->getX() . ', ' . $unit->getPoint()->getY() . ')'
        );
    }

    public function killAndEatMessage(Animal $stronger, Animal $weaker): void
    {
        $strongerStrength = $stronger->getStrength();
        $weakerStrength = $weaker->getStrength();
        $this->newMessage($stronger->getMapState(),
            $stronger->getName() . ' (' . $strongerStrength . ') убил и съел '
            . $weaker->getName() . ' (' . $weakerStrength . '), сила увеличена до ' . ($strongerStrength + $weakerStrength)
        );
    }

    public function hurtMessage(Animal $stronger, Animal $weaker): void
    {
        $strongerStrength = $stronger->getStrength();
        $weakerStrength = $weaker->getStrength();
        $this->newMessage($stronger->getMapState(),
            $stronger->getName() . ' (' . $strongerStrength . ') ранил ' . $weaker->getName()
            . ' (' . $weakerStrength . '), сила последнего уменьшена до ' . ($weakerStrength - $strongerStrength)
        );
    }

    public function eatPlantMessage(Animal $walker, SimplePlant $plant): void
    {
        $walkerStrength = $walker->getStrength();
        $plantFoodPower = $plant->getFoodPower();
        $this->newMessage($walker->getMapState(),
            $walker->getName() . ' (' . $walkerStrength . ') съел ' . $plant->getName()
            . ' (' . $plantFoodPower . '), сила увеличена до ' . ($walkerStrength + $plantFoodPower)
        );
    }
}
