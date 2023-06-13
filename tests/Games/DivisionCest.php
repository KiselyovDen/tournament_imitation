<?php


namespace App\Tests\Games;

use App\Dto\GameScoreDto;
use App\Entity\DivisionGameScore;
use App\Entity\Game;
use App\Enum\GameType;
use App\GameResultProcessors\GameResultProcessorCreator;
use App\Model\DivisionTableModel;
use App\Tests\Support\GamesTester;

class DivisionCest
{

    public function _before(GamesTester $I)
    {
        /**
         * @var DivisionTableModel $divisionTableModel
         */
        $divisionTableModel = $I->grabService(DivisionTableModel::class);
        $divisionTableModel->createDivisions(10);
        /**
         * @var GameResultProcessorCreator $creator
         */
        $creator = $I->grabService(GameResultProcessorCreator::class);

        $processor = $creator->createProcessor(GameType::DIVISION);
        $processor->process();
    }

    // tests
    public function divisionGamesCountTest(GamesTester $I)
    {
        $games = $I->grabNumRecords(Game::class, [
            'game_type' => GameType::DIVISION
        ]);
        $I->assertEquals(20, $games, 'Games count should be 20');
    }

    public function divisionResultsTest(GamesTester $I)
    {
        $games = $I->grabEntitiesFromRepository(Game::class, [
            'game_type' => GameType::DIVISION
        ]);

        $totalScores = [];
        /**
         * @var Game $game
         */
        foreach ($games as $game) {
            $team1Id = $game->getTeam1()->getId();
            if (!isset($totalScores[$team1Id])) {
                $totalScores[$team1Id] = new GameScoreDto($game->getTeam1(), $game->getTeam1Score());
            } else {
                $totalScores[$team1Id]->addScore($game->getTeam1Score());
            }
            $team2Id = $game->getTeam2()->getId();
            if (!isset($totalScores[$team2Id])) {
                $totalScores[$team2Id] = new GameScoreDto($game->getTeam2(), $game->getTeam2Score());
            } else {
                $totalScores[$team2Id]->addScore($game->getTeam2Score());
            }
        }

        foreach ($totalScores as $teamId => $totalScoreDto) {
            $I->canSeeInRepository(DivisionGameScore::class, [
                'team' => $teamId,
                'score' => $totalScoreDto->score
            ]);
        }
    }
}
