<?php

namespace App\Model;

use App\Entity\Team;
use App\Enum\Division;
use App\Repository\TeamRepository;
use Faker;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: 'app.division_table')]
final readonly class DivisionTableModel
{
    public function __construct(
        private TeamRepository $teamRepository
    )
    {
    }

    public function createDivisions(int $teamsCount): void
    {
        // delete everything!
        $this->teamRepository->purge();

        $faker = Faker\Factory::create('lv_LV');
        // create divisions
        $divisionCenter = ceil($teamsCount / 2);
        for ($i = 0; $i < $teamsCount; $i++) {
            $team = new Team();
            $team
                ->setDivision($i < $divisionCenter ? Division::A : Division::B)
                ->setTitle($faker->unique()->firstName());
            $this->teamRepository->save($team);
        }
        $this->teamRepository->flush();
    }
}