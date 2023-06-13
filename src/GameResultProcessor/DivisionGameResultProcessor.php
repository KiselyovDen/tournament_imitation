<?php

namespace App\GameResultProcessor;

use App\GameScores\GameScores;
use App\Entity\DivisionGameScore;
use App\Entity\Game;
use App\Enum\Division;
use App\Enum\GameType;
use App\GameCreator\QuarterGameCreator;

class DivisionGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $this->gameRepository->purge();

        $totalScores = [];
        $divisions = Division::cases();
        foreach ($divisions as $division) {
            $totalScores[$division->value] = new GameScores();

            $teams = $this->teamRepository->findBy(['division' => $division]);

            $teamsCount = count($teams);
            for ($i = 0; $i < $teamsCount; $i++) {
                $team1 = $teams[$i];
                for ($j = $i + 1; $j < $teamsCount; $j++) {
                    $team2 = $teams[$j];
                    $game = Game::createGameForTeams($team1, $team2, GameType::DIVISION);
                    $game->generateScores();

                    $this->gameRepository->save($game);

                    $totalScores[$division->value][$team1] = $game->getTeam1Score();
                    $totalScores[$division->value][$team2] = $game->getTeam2Score();
                }
            }

            $totalScores[$division->value]->sort();
        }

        $this->gameRepository->flush();

        // save division scores to separate table
        $this->saveDivisionGameScores($totalScores);

        // creating quarter games
        $quarterGameCreator = new QuarterGameCreator($this->gameRepository);
        $quarterGameCreator->create($totalScores);
    }

    private function saveDivisionGameScores(array $divisionScores): void
    {
        $this->divisionGameScoreRepository->purge();

        foreach ($divisionScores as $scores) {
            foreach ($scores as $i => $totalScore) {
                $divisionGameScore = new DivisionGameScore();
                $divisionGameScore
                    ->setTeam($totalScore->team)
                    ->setScore($totalScore->getScore())
                    ->setBest($i <= 3);

                $this->divisionGameScoreRepository->save($divisionGameScore);
            }
        }

        $this->divisionGameScoreRepository->flush();
    }

}