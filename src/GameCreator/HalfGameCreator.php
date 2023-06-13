<?php

namespace App\GameCreator;

use App\Entity\Game;
use App\Enum\GameType;
use App\GameScores\GameScores;

class HalfGameCreator extends AbstractGameCreator
{
    public function create(GameScores|array $divisionScores): void
    {
        // generating half step stub
        $this->gameRepository->removeGamesByType([GameType::HALF, GameType::FINAL, GameType::BRONZE]);

        for ($i = 0; $i < 2; $i++) {
            $team1 = $divisionScores[$i];
            $team2 = $divisionScores[3 - $i];

            $game = Game::createGameForTeams($team1->team, $team2->team, GameType::HALF);
            $this->gameRepository->save($game);
        }

        $this->gameRepository->flush();
    }
}