<?php

namespace App\GameScores;

use App\Entity\Team;

class GameScores implements \ArrayAccess, \Iterator
{
    private array $scores = [];
    private array $teamIdtoKeys = [];
    private int $maxId = 0;
    private int $position;

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->scores[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->scores[$offset] ?? [];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value <= 0) {
            return;
        }

        if ($offset instanceof Team) {
            if (!isset($this->teamIdtoKeys[$offset->getId()])) {
                $this->scores[$this->maxId] = new GameScoreElement($offset, (int)$value);
                $this->teamIdtoKeys[$offset->getId()] = $this->maxId;
                $this->maxId++;
            } else {
                $key = $this->teamIdtoKeys[$offset->getId()];
                $this->scores[$key]->addScore($value);
            }
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->scores[$offset]);
    }

    public function current(): mixed
    {
        return $this->scores[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->scores[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function sort(): void
    {
        usort($this->scores, static function (GameScoreElement $a, GameScoreElement $b) {
            return -($a->getScore() <=> $b->getScore());
        });
    }
}