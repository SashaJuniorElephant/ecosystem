<?php

namespace App\Service;

use App\Entity\Games;
use App\Entity\Points;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GameBuilder
{
    /**
     * @param array $params
     * @return Games
     */
    public function newGameAtFields(array $params): Games
    {
        return $this->newGame($params);
    }

    /**
     * @param array $params
     * @return Games
     */
    public function newGameAtFile(array $params): Games
    {
        $paramsFromFile = $this->getParamsFromFile($params);

        return $this->newGame($paramsFromFile);
    }

    /**
     * @param array $params
     * @return Games
     */
    private function newGame(array $params): Games
    {
        $game = new Games();
        $game->init($params);
        $this->generatePoints($game);

        return $game;
    }

    /**
     * @param array $params
     * @return array
     */
    private function getParamsFromFile(array $params): array
    {
        $paramsFromFile = $this->parseFile($params['file']);

        return [
            'dimension'             => $paramsFromFile[0],
            'amountSteps'           => $paramsFromFile[1],
            'amountSimplePlants'    => $paramsFromFile[2],
            'amountPoisonPlants'    => $paramsFromFile[3],
            'amountHerbivores'      => $paramsFromFile[4],
            'amountPredators'       => $paramsFromFile[5],
            'amountBigPredators'    => $paramsFromFile[6],
            'amountVisitors'        => $paramsFromFile[7],
            'useSessions'           => $params['useSessions'],
            'name'                  => $params['name'],
        ];
    }

    /**
     * @param UploadedFile $csv
     * @return array
     */
    private function parseFile(UploadedFile $csv): array
    {
        $file = fopen($csv, "r");
        fgetcsv($file); // пропуск первой строки
        $params = fgetcsv($file);
        fclose($file);

        return $params;
    }

    /**
     * @param Games $game
     */
    private function generatePoints(Games $game): void
    {
        $dimension = $game->getDimension();

        foreach (range(1, $dimension) as $x) {
            foreach (range(1, $dimension) as $y) {
                $point = new Points();
                $point->setGame($game);
                $point->setX($x);
                $point->setY($y);
                $game->addPoint($point);
            }
        }
    }
}
