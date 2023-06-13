<?php

namespace App\GameCreator;

use App\GameScores\GameScoreElement;
use App\GameScores\GameScores;
use App\Repository\DivisionGameScoreRepository;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

abstract class AbstractGameCreator
{
    public function __construct(
        protected GameRepository $gameRepository
    ) {
    }

    abstract public function create(GameScores|array $divisionScores): void;
}