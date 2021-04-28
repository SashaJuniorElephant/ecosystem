<?php

namespace App\Controller;

use App\Service\ControlStepsService;
use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ObservationController extends AbstractController
{
    /**
     * @Route(
     *     "/observation",
     *     methods={"GET"},
     *     name="observation"
     * )
     * @param GameService $gameService
     * @param ControlStepsService $stepsService
     * @Security("is_granted('ROLE_USER')")
     * @return Response
     */
    public function createGame(GameService $gameService, ControlStepsService $stepsService)
    {
        $game = $gameService->loadGame();

        return $this->render('ecosystem/observation/observation.html.twig', [
            'title' => 'Наблюдение',
            'game' => $game,
            'mapStates' => $stepsService->comeIntoPlay($game),
        ]);
    }

    /**
     * @Route(
     *     "/observation",
     *     methods={"PUT"},
     *     name="observation_next_step"
     * )
     * @Security("is_granted('ROLE_USER')")
     * @param ControlStepsService $stepsService
     * @param GameService $gameService
     * @return Response
     */
    public function nextStep(ControlStepsService $stepsService, GameService $gameService)
    {
        $game = $gameService->loadGame();

        return $this->render('ecosystem/observation/map_state.html.twig', [
            'game' => $game,
            'mapState' => $stepsService->continuePlay($game),
        ]);
    }
}
