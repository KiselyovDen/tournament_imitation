<?php

namespace App\Registry;

use App\GameScores\GameScoreElement;
use App\Entity\Game;
use App\Enum\GameType;

final class GamesRegistry
{
    protected static self|null $instance = null;

    private array $games = [];

    private function __construct()
    {
    }

    public static function getInstance(): GamesRegistry
    {
        if (self::$instance === null) {
            self::$instance = new GamesRegistry;
        }

        return self::$instance;
    }

    /**
     * @param Game[] $games
     */
    public function loadGames(array $games): void
    {
        foreach ($games as $game) {
            $this->games[$game->getGameType()->value][] = $game;
        }
    }

    public function getGamesByType(GameType $gameType): array
    {
        return $this->games[$gameType->value] ?? [];
    }

    public function getDivisionGames(array $teams): array
    {
        $divisionGames = [];
        foreach ($teams as $team) {
            $divisionGames[$team->getDivision()->name][] = $team;
        }
        return $divisionGames;
    }

    public function getPlayOffGames(): array
    {
        $playOff = [];
        /**
         * @var GameType $gameType
         */
        foreach ([GameType::QUARTER, GameType::HALF, GameType::FINAL, GameType::BRONZE] as $gameType) {
            $playOff[$gameType->value] = $this->games[$gameType->value] ?? [];
        }

        return $playOff;
    }

    public function getWinnersTable(): array
    {
        $winners = [];

        if (isset($this->games[GameType::FINAL->value])) {
            /**
             * @var Game $finalGame
             */
            $finalGame = reset($this->games[GameType::FINAL->value]);
            if ($finalGame->getWinnerScore() > 0) {
                $winners[] = $finalGame->getWinner()->getTitle();
                $winners[] = $finalGame->getLoser()->getTitle();
            }
        }
        if (isset($this->games[GameType::BRONZE->value])) {
            /**
             * @var Game $bronzeGame
             */
            $bronzeGame = reset($this->games[GameType::BRONZE->value]);
            if ($bronzeGame->getWinnerScore() > 0) {
                $winners[] = $bronzeGame->getWinner()->getTitle();
            }
        }

        return $winners;
    }

}