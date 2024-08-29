<?php

namespace App\Entity;

use App\Repository\TypeFraisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeFraisRepository::class)]
class TypeFrais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\OneToMany(mappedBy: 'TypeFrais', targetEntity: FraisDetail::class)]
    private Collection $fraisDetails;

    public function __construct()
    {
        $this->fraisDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, FraisDetail>
     */
    public function getFraisDetails(): Collection
    {
        return $this->fraisDetails;
    }

    public function addFraisDetail(FraisDetail $fraisDetail): static
    {
        if (!$this->fraisDetails->contains($fraisDetail)) {
            $this->fraisDetails->add($fraisDetail);
            $fraisDetail->setTypeFrais($this);
        }

        return $this;
    }

    public function removeFraisDetail(FraisDetail $fraisDetail): static
    {
        if ($this->fraisDetails->removeElement($fraisDetail)) {
            // set the owning side to null (unless already changed)
            if ($fraisDetail->getTypeFrais() === $this) {
                $fraisDetail->setTypeFrais(null);
            }
        }

        return $this;
    }
}
