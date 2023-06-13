<?php

namespace App\Registry;

use App\Dto\GameScoreDto;
use App\Entity\Game;
use App\Enum\GameType;

final class GameRegistry
{
    protected static self|null $instance = null;

    private array $games = [];

    private function __construct()
    {
    }

    public static function getInstance(): GameRegistry
    {
        if (self::$instance === null) {
            self::$instance = new GameRegistry;
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
            $winners[] = new GameScoreDto($finalGame->getWinner(), $finalGame->getWinnerScore());
            $winners[] = new GameScoreDto($finalGame->getLoser(), $finalGame->getLoserScore());
        }
        if (isset($this->games[GameType::BRONZE->value])) {
            /**
             * @var Game $bronzeGame
             */
            $bronzeGame = reset($this->games[GameType::BRONZE->value]);
            $winners[] = new GameScoreDto($bronzeGame->getWinner(), $bronzeGame->getWinnerScore());
        }

        return $winners;
    }

}