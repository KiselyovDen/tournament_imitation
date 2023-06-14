<?php

namespace App\GameResultProcessor;

use App\Enum\GameType;
use App\Repository\DivisionGameScoreRepository;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: 'app.game_result_processor_factory')]
readonly class GameResultProcessorFactory
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
    public function create(GameType $gameType): AbstractGameResultProcessor
    {
        $modelClass = match ($gameType) {
            GameType::DIVISION => DivisionGameResultProcessor::class,
            GameType::QUARTER => QuarterGameResultProcessor::class,
            GameType::HALF => HalfGameResultProcessor::class,
            GameType::FINAL, GameType::BRONZE => FinalGameResultProcessor::class,
        };

        return new $modelClass(
            $this->gameRepository,
            $this->teamRepository,
            $this->divisionGameScoreRepository
        );
    }
}