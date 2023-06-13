<?php

namespace App\GameResultProcessors;

use App\Dto\GameScoreDto;
use App\Entity\DivisionGameScore;
use App\Entity\Game;
use App\Enum\Division;
use App\Enum\GameType;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

class DivisionGameResultProcessor extends AbstractGameResultProcessor
{
    public function process(): void
    {
        $this->gameRepository->purge();

        $totalScores = [];
        $divisions = Division::cases();
        foreach ($divisions as $division) {
            $teams = $this->teamRepository->findBy(['division' => $division]);
            // pre-populate total scores array
            foreach ($teams as $team) {
                $totalScores[$division->value][$team->getId()] = new GameScoreDto($team, 0);
            }

            $teamsCount = count($teams);
            for ($i = 0; $i < $teamsCount; $i++) {
                $team1 = $teams[$i];
                for ($j = $i + 1; $j < $teamsCount; $j++) {
                    $team2 = $teams[$j];

                    $game = Game::createGameForTeams($team1, $team2, GameType::DIVISION);
                    $game->generateScores();
                    $this->gameRepository->save($game);

                    $totalScores[$division->value][$team1->getId()]->addScore($game->getTeam1Score());
                    $totalScores[$division->value][$team2->getId()]->addScore($game->getTeam2Score());
                }
            }
        }

        // sorting scores to help to generate next step
        $this->sortScores($totalScores[Division::A->value]);
        $this->sortScores($totalScores[Division::B->value]);

        $this->createQuarterGames($totalScores);

        // flush DB
        $this->gameRepository->flush();

        // save division scores to separate table
        $this->saveDivisionGameScores($totalScores);
    }

    private function createQuarterGames(array $divisionScores): void
    {
        // generating quarter step stub
        for ($i = 0; $i < 4; $i++) {
            $team1 = $divisionScores[Division::A->value][$i];
            $team2 = $divisionScores[Division::B->value][3 - $i];

            $game = Game::createGameForTeams($team1->team, $team2->team, GameType::QUARTER);
            $this->gameRepository->save($game);
        }
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