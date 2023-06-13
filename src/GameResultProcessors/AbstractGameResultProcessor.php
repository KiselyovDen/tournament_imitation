<?php

namespace App\GameResultProcessors;

use App\Dto\GameScoreDto;
use App\Repository\DivisionGameScoreRepository;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

abstract class AbstractGameResultProcessor
{
    public function __construct(
        protected GameRepository $gameRepository,
        protected TeamRepository $teamRepository,
        protected DivisionGameScoreRepository $divisionGameScoreRepository
    ) {
    }

    abstract public function process(): void;

    /**
     * @param GameScoreDto[] $scores
     */
    protected function sortScores(array &$scores): void
    {
        usort($scores, static function (GameScoreDto $a, GameScoreDto $b) {
            return -($a->getScore() <=> $b->getScore());
        });
    }
}