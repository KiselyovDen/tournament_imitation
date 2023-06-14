<?php

namespace App\GameResultProcessor;

use App\Entity\DivisionGameScore;
use App\Entity\Game;
use App\Enum\Division;
use App\Enum\GameType;
use App\GameScores\GameScoreElement;

class DivisionGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $this->gameRepository->purge();

        foreach (Division::cases() as $division) {
            $teams = $this->teamRepository->findBy(['division' => $division]);

            $teamsCount = count($teams);
            for ($i = 0; $i < $teamsCount; $i++) {
                $team1 = $teams[$i];
                for ($j = $i + 1; $j < $teamsCount; $j++) {
                    $team2 = $teams[$j];
                    $game = Game::createGameForTeams($team1, $team2, GameType::DIVISION);
                    $game->generateScores();

                    $this->gameRepository->save($game);

                    $this->gameScores[$team1] = $game->getTeam1Score();
                    $this->gameScores[$team2] = $game->getTeam2Score();
                }
            }
        }

        $this->gameScores->sort();

        $this->gameRepository->flush();

        // save division scores to separate table
        $this->saveDivisionGameScores();

        $this->notify();
    }

    private function saveDivisionGameScores(): void
    {
        $this->divisionGameScoreRepository->purge();

        $topCounter = [];
        foreach (Division::cases() as $division) {
            $topCounter[$division->value] = 0;
        }

        /**
         * @var GameScoreElement $score
         */
        foreach ($this->gameScores as $score) {
            $best = $topCounter[$score->team->getDivision()->value]++ <= 3;
            $divisionGameScore = new DivisionGameScore();
            $divisionGameScore
                ->setTeam($score->team)
                ->setScore($score->getScore())
                ->setBest($best);

            $this->divisionGameScoreRepository->save($divisionGameScore);
        }

        $this->divisionGameScoreRepository->flush();
    }
}