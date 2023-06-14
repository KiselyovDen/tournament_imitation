<?php

namespace App\GameCreator;

use App\Enum\GameType;
use App\Repository\GameRepository;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: 'app.game_creator_factory')]
readonly class GameCreatorFactory
{
    public function __construct(
        private GameRepository $gameRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(GameType $gameType): AbstractGameCreator
    {
        $modelClass = match ($gameType) {
            GameType::DIVISION => QuarterGameCreator::class,
            GameType::QUARTER => HalfGameCreator::class,
            GameType::HALF => FinalGameCreator::class,
            GameType::FINAL, GameType::BRONZE => throw new Exception('Not possible'),
        };

        return new $modelClass($this->gameRepository);
    }
}