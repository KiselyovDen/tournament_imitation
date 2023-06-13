<?php

namespace App\Dto;

use App\Entity\Team;

class GameScoreDto
{
    public function __construct(
        public readonly Team $team,
        public int $score,
    ) {
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function addScore(int $score): void
    {
        if ($score <= 0) {
            return;
        }

        $this->score += $score;
    }
}