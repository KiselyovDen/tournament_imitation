<?php


namespace App\Tests\Games;

use App\Entity\DivisionGameScore;
use App\Entity\Game;
use App\Entity\Team;
use App\Enum\GameType;
use App\GameResultProcessors\GameResultProcessorCreator;
use App\Model\DivisionTableModel;
use App\Tests\Support\GamesTester;

class PlayOffCest
{
    public function _before(GamesTester $I)
    {
        /**
         * @var DivisionTableModel $divisionTableModel
         */
        $divisionTableModel = $I->grabService(DivisionTableModel::class);
        $divisionTableModel->createDivisions(4);

        $teams = $I->grabEntitiesFromRepository(Team::class);

        $I->haveInRepository(Game::class, [
            'team_1' => $teams[0],
            'team_1_score' => 0,
            'team_2' => $teams[1],
            'team_2_score' => 0,
            'game_type' => GameType::HALF
        ]);

        $I->haveInRepository(Game::class, [
            'team_1' => $teams[2],
            'team_1_score' => 0,
            'team_2' => $teams[3],
            'team_2_score' => 0,
            'game_type' => GameType::HALF
        ]);

        /**
         * @var GameResultProcessorCreator $creator
         */
        $creator = $I->grabService(GameResultProcessorCreator::class);

        $processor =  $creator->createProcessor(GameType::HALF);
        $processor->process();
    }

    public function finalGamesCountTest(GamesTester $I)
    {
        $games = $I->grabNumRecords(Game::class, [
            'game_type' => GameType::FINAL
        ]);
        $I->assertEquals(1, $games, 'Final game should exist');

        $games = $I->grabNumRecords(Game::class, [
            'game_type' => GameType::BRONZE
        ]);
        $I->assertEquals(1, $games, 'Bronze game should exist');
    }

    public function finalGameCreatedFromWinnersTest(GamesTester $I)
    {
        $games = $I->grabEntitiesFromRepository(Game::class, [
            'game_type' => GameType::HALF
        ]);

        $winners = [];
        foreach ($games as $game) {
            $winners[] = $game->getWinner();
        }

        $I->canSeeInRepository(Game::class, [
            'team_1' => $winners[0],
            'team_2' => $winners[1],
            'game_type' => GameType::FINAL
        ]);
    }

    public function bronzeGameCreatedFromLosersTest(GamesTester $I)
    {
        $games = $I->grabEntitiesFromRepository(Game::class, [
            'game_type' => GameType::HALF
        ]);

        $losers = [];
        foreach ($games as $game) {
            $losers[] = $game->getLoser();
        }

        $I->canSeeInRepository(Game::class, [
            'team_1' => $losers[0],
            'team_2' => $losers[1],
            'game_type' => GameType::BRONZE
        ]);
    }
}
