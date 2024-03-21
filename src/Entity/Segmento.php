<?php

namespace App\Entity;

use App\Repository\SegmentoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SegmentoRepository::class)]
class Segmento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $size = null;

    #[ORM\Column(length: 255)]
    private ?string $uidentifier = null;

    #[ORM\ManyToMany(targetEntity: Restaurante::class, mappedBy: 'segmentos', cascade: ['persist'])]
    private Collection $restaurantes;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $popularidadMedia = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $satisfaccionMedia = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $avg_price = null;

    public function __construct()
    {
        $this->restaurantes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getUidentifier(): ?string
    {
        return $this->uidentifier;
    }

    public function setUidentifier(string $uidentifier): static
    {
        $this->uidentifier = $uidentifier;

        return $this;
    }

    /**
     * @return Collection<int, Restaurante>
     */
    public function getRestaurantes(): Collection
    {
        return $this->restaurantes;
    }

    public function addRestaurante(Restaurante $restaurante): static
    {
        if (!$this->restaurantes->contains($restaurante)) {
            $this->restaurantes->add($restaurante);
            $restaurante->addSegmento($this);
        }

        return $this;
    }

    public function removeRestaurante(Restaurante $restaurante): static
    {
        if ($this->restaurantes->removeElement($restaurante)) {
            $restaurante->removeSegmento($this);
        }
        return $this;
    }

    public function getPopularidadMedia(): ?string
    {
        return $this->popularidadMedia;
    }

    public function setPopularidadMedia(?string $popularidadMedia): static
    {
        $this->popularidadMedia = $popularidadMedia;

        return $this;
    }

    public function getSatisfaccionMedia(): ?string
    {
        return $this->satisfaccionMedia;
    }

    public function setSatisfaccionMedia(?string $satisfaccionMedia): static
    {
        $this->satisfaccionMedia = $satisfaccionMedia;

        return $this;
    }

    public function getAvgPrice(): ?string
    {
        return $this->avg_price;
    }

    public function setAvgPrice(?string $avg_price): static
    {
        $this->avg_price = $avg_price;

        return $this;
    }
}
