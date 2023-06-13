<?php

namespace App\GameResultProcessor;

use App\GameScores\GameScoreElement;
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
}