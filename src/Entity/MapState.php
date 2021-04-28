<?php

namespace App\Entity;

use App\Entity\Units\Units;
use App\Service\MapService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MapStateRepository")
 */
class MapState implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Games", inversedBy="mapStates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\Column(type="integer")
     */
    private $step;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Units\Units", mappedBy="mapState", orphanRemoval=true, cascade={"persist"})
     */
    private $units;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoryLogs", mappedBy="mapState", orphanRemoval=true, cascade={"persist"})
     */
    private $historyLogs;

    /**
     * MapState constructor.
     * @param $step
     */
    public function __construct($step = 0)
    {
        $this->step = $step;
        $this->units = new ArrayCollection();
        $this->historyLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Games
    {
        return $this->game;
    }

    public function setGame(?Games $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(int $step): self
    {
        $this->step = $step;

        return $this;
    }

    /**
     * @return Collection|Units[]
     */
    public function getUnits(): Collection
    {
        return $this->units;
    }

    public function addUnit(Units $unit): self
    {
        if (!$this->units->contains($unit)) {
            $this->units[] = $unit;
            $unit->setMapState($this);
        }

        return $this;
    }

    public function removeUnit(Units $unit): self
    {
        if ($this->units->contains($unit)) {
            $this->units->removeElement($unit);
            // set the owning side to null (unless already changed)
            if ($unit->getMapState() === $this) {
                $unit->setMapState(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HistoryLogs[]
     */
    public function getHistoryLogs(): Collection
    {
        return $this->historyLogs;
    }

    public function addHistoryLog(HistoryLogs $historyLog): self
    {
        if (!$this->historyLogs->contains($historyLog)) {
            $this->historyLogs[] = $historyLog;
            $historyLog->setMapState($this);
        }

        return $this;
    }

    public function removeHistoryLog(HistoryLogs $historyLog): self
    {
        if ($this->historyLogs->contains($historyLog)) {
            $this->historyLogs->removeElement($historyLog);
            // set the owning side to null (unless already changed)
            if ($historyLog->getMapState() === $this) {
                $historyLog->setMapState(null);
            }
        }

        return $this;
    }

    public function clearHistoryLogs(): self
    {
        $this->historyLogs->clear();

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
        $mapState = [
            'step' => $this->step,
        ];

        foreach ($this->units as $unit) {
            $mapState['units'][] = $unit;
        }

        foreach ($this->historyLogs as $message) {
            $mapState['history_logs'][] = $message;
        }

        return $mapState;
    }
}
