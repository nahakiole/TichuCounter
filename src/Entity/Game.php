<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player_one_team_a;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player_two_team_a;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player_one_team_b;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="games")
     */
    private $player_two_team_b;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Round", mappedBy="game")
     */
    private $rounds;

    public function __construct()
    {
        $this->rounds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerOneTeamA(): ?Player
    {
        return $this->player_one_team_a;
    }

    public function setPlayerOneTeamA(?Player $player_one_team_a): self
    {
        $this->player_one_team_a = $player_one_team_a;

        return $this;
    }

    public function getPlayerTwoTeamA(): ?Player
    {
        return $this->player_two_team_a;
    }

    public function setPlayerTwoTeamA(?Player $player_two_team_a): self
    {
        $this->player_two_team_a = $player_two_team_a;

        return $this;
    }

    public function getPlayerOneTeamB(): ?Player
    {
        return $this->player_one_team_b;
    }

    public function setPlayerOneTeamB(?Player $player_one_team_b): self
    {
        $this->player_one_team_b = $player_one_team_b;

        return $this;
    }

    public function getPlayerTwoTeamB(): ?Player
    {
        return $this->player_two_team_b;
    }

    public function setPlayerTwoTeamB(?Player $player_two_team_b): self
    {
        $this->player_two_team_b = $player_two_team_b;

        return $this;
    }

    /**
     * @return Collection|Round[]
     */
    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    public function addRound(Round $round): self
    {
        if (!$this->rounds->contains($round)) {
            $this->rounds[] = $round;
            $round->setGame($this);
        }

        return $this;
    }

    public function getPointsTeamA(){
        $points = 0;
        foreach ($this->getRounds() as $index => $round) {
            $points += $round->getPointsTeamA();
        }
        return $points;
    }

    public function getPointsTeamB(){
        $points = 0;
        foreach ($this->getRounds() as $index => $round) {
            $points += $round->getPointsTeamB();
        }
        return $points;
    }

    public function removeRound(Round $round): self
    {
        if ($this->rounds->contains($round)) {
            $this->rounds->removeElement($round);
            // set the owning side to null (unless already changed)
            if ($round->getGame() === $this) {
                $round->setGame(null);
            }
        }

        return $this;
    }
}
