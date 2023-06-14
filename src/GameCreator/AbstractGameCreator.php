<?php

namespace App\GameCreator;

use App\GameResultProcessor\AbstractGameResultProcessor;
use App\Repository\GameRepository;

abstract class AbstractGameCreator implements \SplObserver
{
    public function __construct(
        protected GameRepository $gameRepository
    ) {
    }

    abstract public function update(AbstractGameResultProcessor|\SplSubject $subject): void;
}