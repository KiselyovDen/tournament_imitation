<?php

namespace App\Entity;

use App\Enum\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Faker;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[Orm\Index(fields: ["game_type"], name: "game_type")]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Team $team_1;

    #[ORM\Column]
    private int $team_1_score = 0;

    #[ORM\ManyToOne(cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Team $team_2;

    #[ORM\Column]
    private int $team_2_score = 0;

    #[ORM\Column(length: 20, nullable: false)]
    private GameType $game_type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam1(): Team
    {
        return $this->team_1;
    }

    public function setTeam1(Team $team_1): static
    {
        $this->team_1 = $team_1;

        return $this;
    }

    public function getTeam1Score(): int
    {
        return $this->team_1_score;
    }

    public function setTeam1Score(int $team_1_score): static
    {
        $this->team_1_score = $team_1_score;

        return $this;
    }

    public function getTeam2(): Team
    {
        return $this->team_2;
    }

    public function setTeam2(Team $team_2): static
    {
        $this->team_2 = $team_2;

        return $this;
    }

    public function getTeam2Score(): int
    {
        return $this->team_2_score;
    }

    public function setTeam2Score(int $team_2_score): static
    {
        $this->team_2_score = $team_2_score;

        return $this;
    }

    public function getGameType(): GameType
    {
        return $this->game_type;
    }

    public function setGameType(GameType $game_type): static
    {
        $this->game_type = $game_type;

        return $this;
    }

    public function getWinner(): Team
    {
        return $this->team_1_score >= $this->team_2_score ? $this->team_1 : $this->team_2;
    }

    public function getWinnerScore(): int
    {
        return max($this->team_1_score, $this->team_2_score);
    }

    public function getLoser(): Team
    {
        return $this->team_1_score < $this->team_2_score ? $this->team_1 : $this->team_2;
    }

    public function getLoserScore(): int
    {
        return min($this->team_1_score, $this->team_2_score);
    }

    public function generateScores(): void
    {
        $faker = Faker\Factory::create();
        $this
            ->setTeam1Score($faker->unique()->numberBetween(0, 10))
            ->setTeam2Score($faker->unique()->numberBetween(0, 10));
    }


    public static function createGameForTeams(Team $team1, Team $team2, GameType $gameType): Game
    {
        $game = new self();
        $game
            ->setTeam1($team1)
            ->setTeam2($team2)
            ->setGameType($gameType);

        return $game;
    }
}
