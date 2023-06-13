<?php

namespace App\Entity;

use App\Enum\Division;
use App\Enum\GameType;
use App\Registry\GameRegistry;
use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[Orm\Index(fields: ["division"], name: "division")]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 1, nullable: false)]
    private Division $division;

    #[ORM\OneToOne(mappedBy: 'team', cascade: ['remove'])]
    private ?DivisionGameScore $divisionGameScore = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDivision(): Division
    {
        return $this->division;
    }

    public function setDivision(Division $division): static
    {
        $this->division = $division;

        return $this;
    }

    public function getDivisionGameScore(): ?DivisionGameScore
    {
        return $this->divisionGameScore;
    }

    public function setDivisionGameScore(DivisionGameScore $divisionGameScore): static
    {
        // set the owning side of the relation if necessary
        if ($divisionGameScore->getTeam() !== $this) {
            $divisionGameScore->setTeam($this);
        }

        $this->divisionGameScore = $divisionGameScore;

        return $this;
    }

    public function getDivisionGameScoreByOpponent(Team $team2): string
    {
        if ($team2 === $this) {
            return 'x';
        }

        $thisTeam = $this;
        $games = array_filter(
            GameRegistry::getInstance()->getGamesByType(GameType::DIVISION),
            static function (Game $g) use ($team2, $thisTeam) {
                return
                    ($g->getTeam1() === $thisTeam && $g->getTeam2() === $team2)
                    ||
                    ($g->getTeam1() === $team2 && $g->getTeam2() === $thisTeam);
            }
        );
        if (count($games) === 0) {
            return '0';
        }
        /**
         * @var Game $game
         */
        $game = array_shift($games);

        return $game->getTeam1() === $this ?
            $game->getTeam1Score() . ' - ' . $game->getTeam2Score()
            :
            $game->getTeam2Score() . ' - ' . $game->getTeam1Score();
    }
}
