<?php

namespace App\GameResultProcessors;

use App\Dto\GameScoreDto;
use App\Entity\Game;
use App\Enum\GameType;

class QuarterGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $games = $this->gameRepository->findBy(['game_type' => GameType::QUARTER]);
        foreach ($games as $game) {
            $game->generateScores();

            $winners[] = new GameScoreDto($game->getWinner(), $game->getWinnerScore());
        }
        $this->sortScores($winners);

        // generating half step stub
        $this->gameRepository->removeGamesByType([GameType::HALF, GameType::FINAL, GameType::BRONZE]);

        $winnersCount = count($winners);
        for ($i = 0; $i < $winnersCount / 2; $i++) {
            $team1 = $winners[$i];
            $team2 = $winners[($winnersCount - 1) - $i];

            $game = Game::createGameForTeams($team1->team, $team2->team, GameType::HALF);
            $this->gameRepository->save($game);
        }

        $this->gameRepository->flush();
    }
}