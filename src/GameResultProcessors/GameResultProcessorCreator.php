<?php

namespace App\GameResultProcessors;

use App\Enum\GameType;
use App\Repository\DivisionGameScoreRepository;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: 'app.games_result_processor_creator')]
readonly class GameResultProcessorCreator
{
    public function __construct(
        private GameRepository $gameRepository,
        private TeamRepository $teamRepository,
        private DivisionGameScoreRepository $divisionGameScoreRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function createProcessor(GameType $gameType): AbstractGameResultProcessor
    {
        $modelClass = match ($gameType) {
            GameType::DIVISION => DivisionGameResultProcessor::class,
            GameType::QUARTER => QuarterGameResultProcessor::class,
            GameType::HALF => HalfGameResultProcessor::class,
            GameType::FINAL, GameType::BRONZE => throw new Exception('To be implemented'),
        };

        return new $modelClass(
            $this->gameRepository,
            $this->teamRepository,
            $this->divisionGameScoreRepository
        );
    }
}