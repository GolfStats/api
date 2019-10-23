<?php

namespace App\Entity;


use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScorecardRepository")
 */
class Scorecard
{

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="scorecards")
     */
    private $player;

    /**
     * @var Course
     * @ORM\ManyToOne(targetEntity="App\Entity\Course")
     */
    private $course;

    /**
     * @var Score[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Score")
     * @ORM\JoinTable(
     *     name="scorecards_scores",
     *     joinColumns={@ORM\JoinColumn(name="scorecard_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="score_id", referencedColumnName="id")}
     * )
     */
    private $scores;
    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $date;
    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $playerIndex;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->playerIndex = 54.0;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @param Player $player
     * @return Scorecard
     */
    public function setPlayer(Player $player): self
    {
        $this->player = $player;
        return $this;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @param Course $course
     * @return Scorecard
     */
    public function setCourse(Course $course): self
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return Score[]|Collection
     */
    public function getScores(): array
    {
        return $this->scores;
    }

    /**
     * @param Score[] $scores
     * @return Scorecard
     */
    public function setScores(array $scores): self
    {
        $this->scores = $scores;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return Scorecard
     */
    public function setDate(DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return float
     */
    public function getPlayerIndex(): float
    {
        return $this->playerIndex;
    }

    /**
     * @param float $playerIndex
     * @return Scorecard
     */
    public function setPlayerIndex(float $playerIndex): self
    {
        $this->playerIndex = $playerIndex;
        return $this;
    }

    /**
     * @return string?
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Scorecard
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
}