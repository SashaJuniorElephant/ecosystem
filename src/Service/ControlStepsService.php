<?php

namespace App\Service;

use App\Entity\Games;
use App\Entity\MapState;
use App\Entity\Units\AnimalKingdom;
use App\Entity\Units\Units;
use App\Entity\Units\Visitor;
use Generator;

class ControlStepsService
{
    /**
     * @var MapService
     */
    private $mapService;

    /**
     * @var GameService
     */
    private $gameService;

    /**
     * @var MovementService
     */
    private $movementService;

    /**
     * @var VisitorLogsService
     */
    private $visitorLogsService;

    /**
     * @var UnitCollaborationsService
     */
    private $collaborationsService;

    public function __construct(
        MapService $mapService,
        GameService $gameService,
        MovementService $movementService,
        VisitorLogsService $visitorLogsService,
        UnitCollaborationsService $collaborationsService
    )
    {
        $this->mapService = $mapService;
        $this->gameService = $gameService;
        $this->movementService = $movementService;
        $this->visitorLogsService = $visitorLogsService;
        $this->collaborationsService = $collaborationsService;
    }

    public function comeIntoPlay(Games $game): Generator
    {
        $map = $this->mapService->getLastMapState($game);
        $amountSteps = $game->getSteps();

        do {
            yield $map;
            if ($map->getStep() >= $amountSteps) {
                $this->gameService->saveGame($game);
                break;
            } else {
                $map = $this->mapService->getNextStepMapState($map);
                $this->doStepsWithAllUnits($map);
            }
        } while (true);
    }

    public function continuePlay(Games $game): MapState
    {
        $map = $this->mapService->getLastMapState($game);
        $map = $this->mapService->getNextStepMapState($map);
        $this->doStepsWithAllUnits($map);
        $this->gameService->saveGame($game);

        return $map;
    }

    private function doStepsWithAllUnits(MapState $map): void
    {
        /** @var Units $unit */
        foreach ($map->getUnits() as $unit) {
            if ($unit instanceof AnimalKingdom && !$unit->isEaten()) {
                $this->doUnitStep($unit);
                if ($map->getGame()->isUseSessions() && $unit instanceof Visitor) {
                    $this->visitorLogsService->saveToFile($unit);
                }
            }
        }
    }

    private function doUnitStep(AnimalKingdom $unit): void
    {
        $this->movementService->makeAutoStep($unit);
        $this->collaborationsService->entrance($unit);
    }
}
