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
     * @ORM\Column(type="string", length=36)
     */
    private $reference;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxPoints;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="game", orphanRemoval=true)
     */
    private $players;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Question")
     */
    private $playedQuestions;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currentTurn;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Player", cascade={"persist", "remove"})
     */
    private $currentPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question")
     */
    private $currentQuestion;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $state;

    const STATE_NOT_STARTED = "not started";
    const STATE_PLAYING     = "playing";
    const STATE_FINISHED    = "finished";

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->playedQuestions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getMaxPoints(): ?int
    {
        return $this->maxPoints;
    }

    public function setMaxPoints(int $maxPoints): self
    {
        $this->maxPoints = $maxPoints;

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setGame($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getGame() === $this) {
                $player->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getPlayedQuestions(): Collection
    {
        return $this->playedQuestions;
    }

    public function addPlayedQuestion(Question $playedQuestion): self
    {
        if (!$this->playedQuestions->contains($playedQuestion)) {
            $this->playedQuestions[] = $playedQuestion;
        }

        return $this;
    }

    public function removePlayedQuestion(Question $playedQuestion): self
    {
        if ($this->playedQuestions->contains($playedQuestion)) {
            $this->playedQuestions->removeElement($playedQuestion);
        }

        return $this;
    }

    public function getCurrentTurn(): ?int
    {
        return $this->currentTurn;
    }

    public function setCurrentTurn(?int $currentTurn): self
    {
        $this->currentTurn = $currentTurn;

        return $this;
    }

    public function getCurrentPlayer(): ?Player
    {
        return $this->currentPlayer;
    }

    public function setCurrentPlayer(?Player $currentPlayer): self
    {
        $this->currentPlayer = $currentPlayer;

        return $this;
    }

    public function getCurrentQuestion(): ?Question
    {
        return $this->currentQuestion;
    }

    public function setCurrentQuestion(?Question $currentQuestion): self
    {
        $this->currentQuestion = $currentQuestion;

        return $this;
    }

    public function nextTurn()
    {
        $this->currentTurn++;
        $this->currentPlayer = $this->players->get(0);
    }

    public function canStart(): bool
    {
        return $this->players->count() > 1;
    }

    public function started(): bool
    {
        return $this->state !== self::STATE_NOT_STARTED;
    }

    public function start()
    {
        $this->currentTurn = 1;
        $this->state = self::STATE_PLAYING;
        $this->currentPlayer = $this->players->get(0);
    }

    public function finish()
    {
        $this->state = self::STATE_FINISHED;
    }

    public function getNextPlayer(): bool
    {
        $currentIndex = $this->players->indexOf($this->currentPlayer);
        if ($currentIndex >= ($this->players->count() - 1)) {
            return null;
        }
        return $this->players->get($currentIndex + 1);
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }
}
