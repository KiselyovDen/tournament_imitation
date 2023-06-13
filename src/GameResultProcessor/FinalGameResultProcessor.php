<?php

namespace App\GameResultProcessor;

use App\Entity\Game;
use App\Enum\GameType;

class FinalGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $games = $this->gameRepository->findBy(['game_type' => GameType::FINAL]);
        if (count($games) > 0) {
            /**
             * @var Game $game
             */
            $game = $games[0];
            $game->generateScores();
        }

        $games = $this->gameRepository->findBy(['game_type' => GameType::BRONZE]);
        if (count($games) > 0) {
            /**
             * @var Game $game
             */
            $game = $games[0];
            $game->generateScores();
        }

        $this->gameRepository->flush();
    }
}