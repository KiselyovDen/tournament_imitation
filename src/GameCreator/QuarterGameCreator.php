<?php

namespace App\GameCreator;

use App\Entity\Game;
use App\Enum\Division;
use App\Enum\GameType;
use App\GameScores\GameScores;

class QuarterGameCreator extends AbstractGameCreator
{
    public function create(GameScores|array $divisionScores): void
    {
        for ($i = 0; $i < 4; $i++) {
            $team1 = $divisionScores[Division::A->value][$i];
            $team2 = $divisionScores[Division::B->value][3 - $i];

            $game = Game::createGameForTeams($team1->team, $team2->team, GameType::QUARTER);
            $this->gameRepository->save($game);
        }

        $this->gameRepository->flush();
    }
}