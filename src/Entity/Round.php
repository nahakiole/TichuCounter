<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoundRepository")
 */
class Round
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $points_team_a;

    /**
     * @ORM\Column(type="integer")
     */
    private $points_team_b;

    /**
     * @ORM\Column(type="datetime")
     */
    private $finish_time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="rounds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointsTeamA(): ?int
    {
        return $this->points_team_a;
    }

    public function setPointsTeamA(int $points_team_a): self
    {
        $this->points_team_a = $points_team_a;

        return $this;
    }

    public function getPointsTeamB(): ?int
    {
        return $this->points_team_b;
    }

    public function setPointsTeamB(int $points_team_b): self
    {
        $this->points_team_b = $points_team_b;

        return $this;
    }

    public function getFinishTime(): ?\DateTimeInterface
    {
        return $this->finish_time;
    }

    public function setFinishTime(\DateTimeInterface $finish_time): self
    {
        $this->finish_time = $finish_time;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
