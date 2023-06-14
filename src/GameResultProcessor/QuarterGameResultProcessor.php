<?php

namespace App\GameResultProcessor;

use App\Enum\GameType;

class QuarterGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $games = $this->gameRepository->findBy(['game_type' => GameType::QUARTER]);
        foreach ($games as $game) {
            $game->generateScores();

            $this->gameScores[$game->getWinner()] = $game->getWinnerScore();
        }
        $this->gameRepository->flush();

        $this->gameScores->sort();
        $this->notify();
    }
}