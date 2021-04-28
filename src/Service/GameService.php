<?php

namespace App\Service;

use App\Entity\Games;
use App\Repository\GamesRepository;
use App\Repository\MapStateRepository;
use App\Repository\PointsRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var GamesRepository
     */
    private $gamesRepository;

    /**
     * @var PointsRepository
     */
    private $pointsRepository;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var GameBuilder
     */
    private $gameBuilder;

    /**
     * @var MapBuilder
     */
    private $mapBuilder;
    /**
     * @var MapStateRepository
     */
    private $mapStateRepository;

    public function __construct(
        EntityManagerInterface $em,
        GamesRepository $gamesRepository,
        PointsRepository $pointsRepository,
        MapStateRepository $mapStateRepository,
        SessionInterface $session,
        GameBuilder $gameBuilder,
        MapBuilder $mapBuilder
    )
    {
        $this->em = $em;
        $this->gamesRepository = $gamesRepository;
        $this->pointsRepository = $pointsRepository;
        $this->session = $session;
        $this->gameBuilder = $gameBuilder;
        $this->mapBuilder = $mapBuilder;
        $this->mapStateRepository = $mapStateRepository;
    }

    /**
     * @param Games $game
     * @return void
     */
    public function saveGame(Games $game): void
    {
        if (!$game->isUseSessions()) {
            $this->em->persist($game);
            $this->em->flush();
        }
        $this->session->set('game', $game);
    }

    /**
     * @return Games
     */
    public function loadGame(): Games
    {
        /** @var Games $game */
        $game = $this->session->get('game');

        if (!$game->isUseSessions()) {
            $loadedGame = $this->gamesRepository->find($game->getId());
            $loadedGame->setUseSessions(false);
            $this->loadPoints($loadedGame);

            return $loadedGame;
        } else {
            return $game;
        }
    }

    public function loadGameAndRandMapState(int $id): Games
    {
        $game = $this->gamesRepository->findById($id);
        $mapStates = $game->getMapStates()->toArray();
        $map = $mapStates[array_rand($mapStates)];
        $game->clearMapStates()->addMapState($map);

        return $game;
    }

    private function loadPoints(Games $game): void
    {
        $points = $this->pointsRepository->findByGameId($game->getId());
        $game->setPoints($points);
    }

    public function processingForms(FormInterface $form): void
    {
        $params = $form->getData();

        switch ($form->getName()) {
            case 'manual_menu':
                $game = $this->gameBuilder->newGameAtFields($params);
                $this->mapBuilder->newMap($game);
                break;
            case 'csv_menu':
                $game = $this->gameBuilder->newGameAtFile($params);
                $this->mapBuilder->newMap($game);
                break;
            case 'continue_menu':
                /** @var Games $game */
                $game = $params['game'];
                $game->setUseSessions(false);
                break;
            default:
                throw new InvalidArgumentException("Получена неизвестная форма");
        }

        $this->saveGame($game);
    }
}
