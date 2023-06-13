<?php

namespace App\Entity;

use App\Repository\DivisionGameScoreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DivisionGameScoreRepository::class)]
class DivisionGameScore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'divisionGameScore', cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Team $team;

    #[ORM\Column(nullable: false)]
    private int $score ;

    #[ORM\Column(nullable: false)]
    private bool $best = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBest(): bool
    {
        return $this->best;
    }

    /**
     * @param bool $best
     */
    public function setBest(bool $best): void
    {
        $this->best = $best;
    }
}
