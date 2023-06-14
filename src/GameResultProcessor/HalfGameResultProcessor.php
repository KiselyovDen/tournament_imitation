<?php

namespace App\GameResultProcessor;

use App\Enum\GameType;

class HalfGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $games = $this->gameRepository->findBy(['game_type' => GameType::HALF]);

        foreach ($games as $game) {
            $game->generateScores();

            $this->gameScores[$game->getWinner()] = $game->getWinnerScore();
            $this->gameScores[$game->getLoser()] = $game->getLoserScore();
        }

        $this->gameRepository->flush();

        $this->notify();
    }
}