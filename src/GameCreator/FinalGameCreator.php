<?php

namespace App\GameCreator;

use App\Entity\Game;
use App\Enum\GameType;
use App\GameResultProcessor\AbstractGameResultProcessor;

class FinalGameCreator extends AbstractGameCreator
{
    public function update(AbstractGameResultProcessor|\SplSubject $subject): void
    {
        $this->gameRepository->removeGamesByType([GameType::FINAL, GameType::BRONZE]);

        $divisionScores = $subject->getGameScores();

        // Final game, two winners
        $game = Game::createGameForTeams($divisionScores[0]->team, $divisionScores[2]->team, GameType::FINAL);
        $this->gameRepository->save($game);

        // Bronze, two losers
        $game = Game::createGameForTeams($divisionScores[1]->team, $divisionScores[3]->team, GameType::BRONZE);
        $this->gameRepository->save($game);

        $this->gameRepository->flush();
    }
}