<?php

namespace App\Controller;

use App\Repository\GamesRepository;
use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RestController extends AbstractController
{
    /**
     * @Route("/getGame", name="getGameById")
     * @param Request $request
     * @param GamesRepository $repository
     * @return bool|float|int|string|null
     */
    public function getGame(Request $request, GamesRepository $repository)
    {
        $id = $request->query->get('gameID');
        if (isset($id)) {
            $game = $repository->findById($id);
            if ($game) {
                return $this->json($game);
            }
        }

        return $this->json(new \stdClass());
    }

    /**
     * @Route("/getGameRandStep", name="getGameRandStep")
     * @param Request $request
     * @param GameService $gameService
     * @return bool|float|int|string|null
     */
    public function getGameRandStep(Request $request, GameService $gameService)
    {
        $id = $request->query->get('gameID');
        if (isset($id)) {
            $game = $gameService->loadGameAndRandMapState($id);
            if ($game) {
                return $this->json($game);
            }
        }

        return $this->json(new \stdClass());
    }
}
