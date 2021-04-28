<?php

namespace App\Entity\Units;

use App\Entity\VisitorLogs;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitsRepository")
 */
class Visitor extends AnimalKingdom implements JsonSerializable
{
/**
     * @ORM\OneToMany(targetEntity="App\Entity\VisitorLogs", mappedBy="unit", orphanRemoval=true, cascade={"persist"})
     */
    private $logs;

    public function __construct()
    {
        $this->logs = new ArrayCollection();
    }

    public function __clone()
    {
        $this->logs = new ArrayCollection();
        parent::__clone();
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->extra;
    }

    /**
     * @param int $index
     * @return Visitor
     */
    public function setIndex(int $index): self
    {
        $this->extra = $index;

        return $this;
    }

    /**
     * @return Collection|VisitorLogs[]
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(VisitorLogs $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setUnit($this);
        }

        return $this;
    }

    public function removeLog(VisitorLogs $log): self
    {
        if ($this->logs->contains($log)) {
            $this->logs->removeElement($log);
            // set the owning side to null (unless already changed)
            if ($log->getUnit() === $this) {
                $log->setUnit(null);
            }
        }

        return $this;
    }

    public function clearLogs()
    {
        $this->logs->clear();
    }

    public function jsonSerialize()
    {
        $unit = parent::jsonSerialize();

        foreach ($this->logs as $log) {
            $unit['logs'][] = $log;
        }

        return $unit;
    }


}
