<?php

namespace App\Entity;

use App\Repository\FraisDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FraisDetailRepository::class)]
class FraisDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'fraisDetails')]
    private ?Frais $Frais = null;

    #[ORM\ManyToOne(inversedBy: 'fraisDetails')]
    private ?TypeFrais $TypeFrais = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getFrais(): ?Frais
    {
        return $this->Frais;
    }

    public function setFrais(?Frais $Frais): static
    {
        $this->Frais = $Frais;

        return $this;
    }

    public function getTypeFrais(): ?TypeFrais
    {
        return $this->TypeFrais;
    }

    public function setTypeFrais(?TypeFrais $TypeFrais): static
    {
        $this->TypeFrais = $TypeFrais;

        return $this;
    }
}
