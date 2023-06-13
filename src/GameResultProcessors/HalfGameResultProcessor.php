<?php

namespace App\GameResultProcessors;

use App\Entity\Game;
use App\Enum\GameType;

class HalfGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $games = $this->gameRepository->findBy(['game_type' => GameType::HALF]);

        $winners = [];
        $losers = [];
        foreach ($games as $game) {
            $game->generateScores();

            $winners[] = $game->getWinner();
            $losers[] = $game->getLoser();
        }

        $this->gameRepository->removeGamesByType([GameType::FINAL, GameType::BRONZE]);

        $game = Game::createGameForTeams($winners[0], $winners[1], GameType::FINAL);
        $game->generateScores();
        $this->gameRepository->save($game);

        $game = Game::createGameForTeams($losers[0], $losers[1], GameType::BRONZE);
        $game->generateScores();
        $this->gameRepository->save($game);

        $this->gameRepository->flush();
    }
}