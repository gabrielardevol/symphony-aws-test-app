<?php

namespace App\Entity;

use App\Repository\ArcRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArcRepository::class)]
class Arc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\Column(nullable: true)]
    private ?float $geoloc = null;

    #[ORM\Column(length: 255)]
    private ?string $userId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getGeoloc(): ?float
    {
        return $this->geoloc;
    }

    public function setGeoloc(?float $geoloc): static
    {
        $this->geoloc = $geoloc;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): static
    {
        $this->userId = $userId;

        return $this;
    }
}
