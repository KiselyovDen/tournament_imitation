<?php

namespace App\GameResultProcessor;

use App\GameScores\GameScores;
use App\Repository\DivisionGameScoreRepository;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;

abstract class AbstractGameResultProcessor implements \SplSubject
{
    protected \SplObjectStorage $observers;

    protected GameScores $gameScores;

    public function __construct(
        protected GameRepository $gameRepository,
        protected TeamRepository $teamRepository,
        protected DivisionGameScoreRepository $divisionGameScoreRepository
    ) {
        $this->observers = new \SplObjectStorage();
        $this->gameScores = new GameScores();
    }

    public function getGameScores(): GameScores
    {
        return $this->gameScores;
    }

    public function attach(\SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    abstract public function process(): void;
}