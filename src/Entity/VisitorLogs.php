<?php

namespace App\Entity;

use App\Entity\Units\Visitor;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VisitorLogsRepository")
 */
class VisitorLogs implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Units\Visitor", inversedBy="logs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;


    public function __clone()
    {
        $this->id = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUnit(): ?Visitor
    {
        return $this->unit;
    }

    public function setUnit(?Visitor $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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
        return ['message' => $this->message];
    }
}
