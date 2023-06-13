<?php

namespace App\Tests\Games;

use App\Entity\Team;
use App\Model\DivisionTableModel;
use App\Tests\Support\GamesTester;

class TeamsCest
{
    private DivisionTableModel $divisionTableModel;

    public function _before(GamesTester $I)
    {
        /**
         * @var DivisionTableModel $service
         */
        $this->divisionTableModel = $I->grabService(DivisionTableModel::class);
    }

    public function teamCreationEvenTest(GamesTester $I): void
    {
        $this->divisionTableModel->createDivisions(10);

        $numRecords = $I->grabNumRecords(Team::class, [
            'division' => 'A'
        ]);
        $I->assertEquals(5, $numRecords, 'Division A count should be 5');

        $numRecords = $I->grabNumRecords(Team::class, [
            'division' => 'B'
        ]);
        $I->assertEquals(5, $numRecords, 'Division B count should be 5');
    }

    public function teamCreationOddTest(GamesTester $I): void
    {
        $this->divisionTableModel->createDivisions(11);

        $numRecords = $I->grabNumRecords(Team::class, [
            'division' => 'A'
        ]);
        $I->assertEquals(6, $numRecords, 'Division A count should be 6');

        $numRecords = $I->grabNumRecords(Team::class, [
            'division' => 'B'
        ]);
        $I->assertEquals(5, $numRecords, 'Division A count should be 5');
    }
}
