<?php

namespace App\GameResultProcessor;

use App\Entity\Game;
use App\Enum\GameType;
use App\GameCreator\FinalGameCreator;

class HalfGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $games = $this->gameRepository->findBy(['game_type' => GameType::HALF]);

        $totalScores = [
            GameType::FINAL->value => [],
            GameType::BRONZE->value => []
        ];
        foreach ($games as $game) {
            $game->generateScores();

            $totalScores[GameType::FINAL->value][] = $game->getWinner();
            $totalScores[GameType::BRONZE->value][] = $game->getLoser();
        }

        $this->gameRepository->flush();

        $finalGameCreator = new FinalGameCreator($this->gameRepository);
        $finalGameCreator->create($totalScores);
    }
}