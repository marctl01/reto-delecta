<?php

namespace App\Entity;

use App\Repository\RestauranteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestauranteRepository::class)]
class Restaurante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street_address = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 6)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 6)]
    private ?string $longitude = null;

    #[ORM\Column(length: 255)]
    private ?string $city_name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $popularity_rate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $satisfaction_rate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $last_avg_price = null;

    #[ORM\Column]
    private ?int $total_reviews = null;

    #[ORM\Column(length: 255)]
    private ?string $uidentifier = null;

    #[ORM\ManyToMany(targetEntity: Segmento::class, inversedBy: 'restaurantes')]
    private Collection $segmentos;

    public function __construct()
    {
        $this->segmentos = new ArrayCollection();
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

    public function getStreetAddress(): ?string
    {
        return $this->street_address;
    }

    public function setStreetAddress(?string $street_address): static
    {
        $this->street_address = $street_address;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCityName(): ?string
    {
        return $this->city_name;
    }

    public function setCityName(string $city_name): static
    {
        $this->city_name = $city_name;

        return $this;
    }

    public function getPopularityRate(): ?string
    {
        return $this->popularity_rate;
    }

    public function setPopularityRate(string $popularity_rate): static
    {
        $this->popularity_rate = $popularity_rate;

        return $this;
    }

    public function getSatisfactionRate(): ?string
    {
        return $this->satisfaction_rate;
    }
    
    public function setSatisfactionRate(?string $satisfaction_rate): static
    {
        $this->satisfaction_rate = $satisfaction_rate;
    
        return $this;
    }

    public function getLastAvgPrice(): ?string
    {
        return $this->last_avg_price;
    }

    public function setLastAvgPrice(string $last_avg_price): static
    {
        $this->last_avg_price = $last_avg_price;

        return $this;
    }

    public function getTotalReviews(): ?int
    {
        return $this->total_reviews;
    }

    public function setTotalReviews(int $total_reviews): static
    {
        $this->total_reviews = $total_reviews;

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
     * @return Collection<int, Segmento>
     */
    public function getSegmentos(): Collection
    {
        return $this->segmentos;
    }

    public function addSegmento(Segmento $segmento): static
    {
        if (!$this->segmentos->contains($segmento)) {
            $this->segmentos->add($segmento);
        }

        return $this;
    }

    public function removeSegmento(Segmento $segmento): static
    {
        $this->segmentos->removeElement($segmento);

        return $this;
    }
}
