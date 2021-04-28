<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GamesRepository")
 */
class Games implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $dimension;

    /**
     * @ORM\Column(type="integer")
     */
    private $steps;

    /**
     * @ORM\Column(type="integer")
     */
    private $simplePlants;

    /**
     * @ORM\Column(type="integer")
     */
    private $poisonPlants;

    /**
     * @ORM\Column(type="integer")
     */
    private $herbivores;

    /**
     * @ORM\Column(type="integer")
     */
    private $predators;

    /**
     * @ORM\Column(type="integer")
     */
    private $bigPredators;

    /**
     * @ORM\Column(type="integer")
     */
    private $visitors;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MapState", mappedBy="game", orphanRemoval=true, cascade={"persist"})
     */
    private $mapStates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Points", mappedBy="game", orphanRemoval=true, cascade={"persist"})
     * @var Collection|Points[]
     */
    private $points;

    /**
     * @var bool
     */
    private $useSessions;

    public function __construct()
    {
        $this->mapStates = new ArrayCollection();
        $this->points = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDimension(): ?int
    {
        return $this->dimension;
    }

    public function setDimension(int $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getSteps(): ?int
    {
        return $this->steps;
    }

    public function setSteps(int $steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    public function getSimplePlants(): ?int
    {
        return $this->simplePlants;
    }

    public function setSimplePlants(int $simplePlants): self
    {
        $this->simplePlants = $simplePlants;

        return $this;
    }

    public function getPoisonPlants(): ?int
    {
        return $this->poisonPlants;
    }

    public function setPoisonPlants(int $poisonPlants): self
    {
        $this->poisonPlants = $poisonPlants;

        return $this;
    }

    public function getHerbivores(): ?int
    {
        return $this->herbivores;
    }

    public function setHerbivores(int $herbivores): self
    {
        $this->herbivores = $herbivores;

        return $this;
    }

    public function getPredators(): ?int
    {
        return $this->predators;
    }

    public function setPredators(int $predators): self
    {
        $this->predators = $predators;

        return $this;
    }

    public function getBigPredators(): ?int
    {
        return $this->bigPredators;
    }

    public function setBigPredators(int $bigPredators): self
    {
        $this->bigPredators = $bigPredators;

        return $this;
    }

    public function getVisitors(): ?int
    {
        return $this->visitors;
    }

    public function setVisitors(int $visitors): self
    {
        $this->visitors = $visitors;

        return $this;
    }

    public function init(array $params): self
    {
        $this->name         = $params['name'];
        $this->dimension    = $params['dimension'];
        $this->steps        = $params['amountSteps'];
        $this->simplePlants = $params['amountSimplePlants'];
        $this->poisonPlants = $params['amountPoisonPlants'];
        $this->herbivores   = $params['amountHerbivores'];
        $this->predators    = $params['amountPredators'];
        $this->bigPredators = $params['amountBigPredators'];
        $this->visitors     = $params['amountVisitors'];
        $this->useSessions  = $params['useSessions'];

        return $this;
    }

    /**
     * @return Collection|MapState[]
     */
    public function getMapStates(): Collection
    {
        return $this->mapStates;
    }

    public function addMapState(MapState $mapState): self
    {
        if (!$this->mapStates->contains($mapState)) {
            $this->mapStates[] = $mapState;
            $mapState->setGame($this);
        }

        return $this;
    }

    public function removeMapState(MapState $mapState): self
    {
        if ($this->mapStates->contains($mapState)) {
            $this->mapStates->removeElement($mapState);
            // set the owning side to null (unless already changed)
            if ($mapState->getGame() === $this) {
                $mapState->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Points[]
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    /**
     * @param int $x
     * @param int $y
     * @return Points
     */
    public function getPointByCoords(int $x, int $y): Points
    {
        foreach ($this->points as $point) {
            if ($point->getX() == $x && $point->getY() == $y) {
                return $point;
            }
        }
        throw new InvalidArgumentException("Запрашиваемая точка на карте отсутствует");
    }

    public function addPoint(Points $point): self
    {
        if (!$this->points->contains($point)) {
            $this->points[] = $point;
            $point->setGame($this);
        }

        return $this;
    }

    public function removePoint(Points $point): self
    {
        if ($this->points->contains($point)) {
            $this->points->removeElement($point);
            // set the owning side to null (unless already changed)
            if ($point->getGame() === $this) {
                $point->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @param Points[] $points
     * @return Games
     */
    public function setPoints(array $points): self
    {
        $this->points->clear();

        foreach ($points as $point) {
            $this->addPoint($point);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isUseSessions(): bool
    {
        return $this->useSessions;
    }

    /**
     * @param bool $useSessions
     * @return Games
     */
    public function setUseSessions(bool $useSessions): self
    {
        $this->useSessions = $useSessions;

        return $this;
    }

    public function clearPoints(): void
    {
        foreach ($this->points as $point) {
            $point->clearUnits();
        }
    }

    public function clearMapStates(): self
    {
        $this->mapStates = new ArrayCollection();

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $game = [
            'id'            => $this->id,
            'name'          => $this->name,
            'dimension'     => $this->dimension,
            'steps'         => $this->steps,
            'simple_plants' => $this->simplePlants,
            'poison_plants' => $this->poisonPlants,
            'herbivores'    => $this->herbivores,
            'predators'     => $this->predators,
            'big_predators' => $this->bigPredators,
            'visitors'      => $this->visitors,
        ];

        foreach ($this->points as $point) {
            $game['points'][] = $point;
        }

        foreach ($this->mapStates as $mapState) {
            $game['map_states'][] = $mapState;
        }

        return $game;
    }
}
