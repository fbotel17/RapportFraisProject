<?php

namespace App\Entity;

use App\Repository\FraisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FraisRepository::class)]
class Frais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;


    private ?\DateTimeInterface $heureVolantTime = null;
    private ?\DateTimeInterface $heuresTotalesTime = null;


    #[ORM\Column(type: Types::FLOAT)]
    private ?float $heure_volant = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $heures_totales = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $total_frais = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $petit_dejeuner = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $repas_midi = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $repas_soir = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $nuit = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $dimanche = false;

    #[ORM\ManyToOne(inversedBy: 'frais')]
    private ?Chauffeur $chauffeur = null;

    #[ORM\OneToMany(mappedBy: 'frais', targetEntity: FraisDetail::class)]
    private Collection $fraisDetails;

    public function __construct()
    {
        $this->fraisDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getHeureVolant(): ?float
    {
        return $this->heure_volant;
    }

    public function setHeureVolant(?float $heure_volant): self
    {
        $this->heure_volant = $heure_volant;

        return $this;
    }

    public function getHeuresTotales(): ?float
    {
        return $this->heures_totales;
    }

    public function setHeuresTotales(?float $heures_totales): self
    {
        $this->heures_totales = $heures_totales;

        return $this;
    }

    public function getHeureVolantTime(): ?\DateTimeInterface
    {
        return $this->heureVolantTime;
    }

    public function setHeureVolantTime(?\DateTimeInterface $heureVolantTime): self
    {
        $this->heureVolantTime = $heureVolantTime;

        if ($heureVolantTime !== null) {
            // Extraire les heures et les minutes de l'objet DateTimeInterface
            $hours = (int) $heureVolantTime->format('G');
            $minutes = (int) $heureVolantTime->format('i');

            // Convertir les heures en minutes et ajouter les minutes extraites
            $this->heure_volant = ($hours * 60) + $minutes;
        }

        return $this;
    }

    public function getHeuresTotalesTime(): ?\DateTimeInterface
    {
        return $this->heuresTotalesTime;
    }

    public function setHeuresTotalesTime(?\DateTimeInterface $heuresTotalesTime): self
    {
        $this->heuresTotalesTime = $heuresTotalesTime;

        if ($heuresTotalesTime !== null) {
            // Extraire les heures et les minutes de l'objet DateTimeInterface
            $hours = (int) $heuresTotalesTime->format('G');
            $minutes = (int) $heuresTotalesTime->format('i');

            // Convertir les heures en minutes et ajouter les minutes extraites
            $this->heures_totales = ($hours * 60) + $minutes;
        }

        return $this;
    }



    

    public function getTotalFrais(): ?float
    {
        return $this->total_frais;
    }

    public function setTotalFrais(float $total_frais): static
    {
        $this->total_frais = $total_frais;

        return $this;
    }

    public function getPetitDejeuner(): bool
    {
        return $this->petit_dejeuner;
    }

    public function setPetitDejeuner(bool $petit_dejeuner): static
    {
        $this->petit_dejeuner = $petit_dejeuner;

        return $this;
    }

    public function getRepasMidi(): bool
    {
        return $this->repas_midi;
    }

    public function setRepasMidi(bool $repas_midi): static
    {
        $this->repas_midi = $repas_midi;

        return $this;
    }

    public function getRepasSoir(): bool
    {
        return $this->repas_soir;
    }

    public function setRepasSoir(bool $repas_soir): static
    {
        $this->repas_soir = $repas_soir;

        return $this;
    }

    public function getNuit(): bool
    {
        return $this->nuit;
    }

    public function setNuit(bool $nuit): static
    {
        $this->nuit = $nuit;

        return $this;
    }

    public function getDimanche(): bool
    {
        return $this->dimanche;
    }

    public function setDimanche(bool $dimanche): static
    {
        $this->dimanche = $dimanche;

        return $this;
    }

    public function getChauffeur(): ?Chauffeur
    {
        return $this->chauffeur;
    }

    public function setChauffeur(?Chauffeur $chauffeur): static
    {
        $this->chauffeur = $chauffeur;

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
            $fraisDetail->setFrais($this);
        }

        return $this;
    }

    public function removeFraisDetail(FraisDetail $fraisDetail): static
    {
        if ($this->fraisDetails->removeElement($fraisDetail)) {
            // set the owning side to null (unless already changed)
            if ($fraisDetail->getFrais() === $this) {
                $fraisDetail->setFrais(null);
            }
        }

        return $this;
    }
}
