<?php

namespace App\Entity\Units;

use App\Entity\MapState;
use App\Entity\Points;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitsRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({
 *     0 = "SimplePlant",
 *     1 = "PoisonPlant",
 *     2 = "Herbivore",
 *     3 = "Predator",
 *     4 = "BigPredator",
 *     5 = "Visitor",
 * })
 */
abstract class Units implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MapState", inversedBy="units")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mapState;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true, name="extra")
     */
    protected $extra;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Points", inversedBy="units")
     * @ORM\JoinColumn(nullable=false)
     * @var Points
     */
    private $point;

    public function __clone()
    {
        $this->id = null;
        $this->mapState = null;
        $this->point = null;
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

    public function getPoint(): ?Points
    {
        return $this->point;
    }

    public function setPoint(?Points $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getMapState(): ?MapState
    {
        return $this->mapState;
    }

    public function setMapState(?MapState $mapState): self
    {
        $this->mapState = $mapState;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'name'  => $this->name,
            'extra' => $this->extra,
        ];
    }
}
