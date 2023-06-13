<?php

namespace App\GameCreator;

use App\Entity\Game;
use App\Enum\GameType;
use App\GameScores\GameScores;

class FinalGameCreator extends AbstractGameCreator
{
    public function create(GameScores|array $divisionScores): void
    {
        $this->gameRepository->removeGamesByType([GameType::FINAL, GameType::BRONZE]);

        $winners = $divisionScores[GameType::FINAL->value];
        $losers = $divisionScores[GameType::BRONZE->value];

        $game = Game::createGameForTeams($winners[0], $winners[1], GameType::FINAL);
        $this->gameRepository->save($game);

        $game = Game::createGameForTeams($losers[0], $losers[1], GameType::BRONZE);
        $this->gameRepository->save($game);

        $this->gameRepository->flush();
    }
}