<?php


namespace App\Tests\Games;

use App\Entity\Game;
use App\Entity\Team;
use App\Enum\GameType;
use App\GameCreator\GameCreatorFactory;
use App\GameResultProcessor\HalfGameResultProcessor;
use App\GameScores\GameScores;
use App\Model\DivisionTableModel;
use App\Tests\Support\GamesTester;
use Codeception\Stub;

class PlayOffCest
{
    private GameScores $gameScores;
    public function _before(GamesTester $I)
    {
        /**
         * @var DivisionTableModel $divisionTableModel
         */
        $divisionTableModel = $I->grabService(DivisionTableModel::class);
        $divisionTableModel->createDivisions(4);

        $teams = $I->grabEntitiesFromRepository(Team::class);

        $this->gameScores = new GameScores();
        $this->gameScores[$teams[0]] = 10;
        $this->gameScores[$teams[1]] = 5;
        $this->gameScores[$teams[2]] = 4;
        $this->gameScores[$teams[3]] = 2;

        /**
         * @var GameCreatorFactory $creator
         */
        $creatorFactory = $I->grabService(GameCreatorFactory::class);

        $processor = Stub::make(HalfGameResultProcessor::class, ['gameScores' => $this->gameScores, 'observers' => new \SplObjectStorage()], $this);
        $processor->attach($creatorFactory->create(GameType::HALF));
        $processor->notify();
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
        $I->canSeeInRepository(Game::class, [
            'team_1' => $this->gameScores[0]->team,
            'team_2' => $this->gameScores[2]->team,
            'game_type' => GameType::FINAL
        ]);
    }

    public function bronzeGameCreatedFromLosersTest(GamesTester $I)
    {
        $I->canSeeInRepository(Game::class, [
            'team_1' => $this->gameScores[1]->team,
            'team_2' => $this->gameScores[3]->team,
            'game_type' => GameType::BRONZE
        ]);
    }
}
