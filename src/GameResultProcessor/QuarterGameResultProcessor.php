<?php

namespace App\GameResultProcessor;

use App\GameCreator\HalfGameCreator;
use App\Enum\GameType;
use App\GameScores\GameScores;

class QuarterGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $games = $this->gameRepository->findBy(['game_type' => GameType::QUARTER]);
        $winners = new GameScores();
        foreach ($games as $game) {
            $game->generateScores();

            $winners[$game->getWinner()] = $game->getWinnerScore();
        }
        $winners->sort();

        $this->gameRepository->flush();

        $halfGameCreator = new HalfGameCreator($this->gameRepository);
        $halfGameCreator->create($winners);
    }
}