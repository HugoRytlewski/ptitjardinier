<?php

namespace App\Entity;

use App\Repository\TaillerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaillerRepository::class)]
class Tailler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $no = null;


    #[ORM\Column]
    private ?float $hauteur = null;

    #[ORM\Column]
    private ?float $longueur = null;

    #[ORM\ManyToOne(inversedBy: 'taillers')]
    #[ORM\JoinColumn(name: "devis_id", referencedColumnName: "no")]
    private ?Devis $devis = null;

    #[ORM\ManyToOne(inversedBy: 'taillers')]
    #[ORM\JoinColumn(name: "haie_code", referencedColumnName: "code")]
    private ?Haie $haie = null;



    public function getNo(): ?int
    {
        return $this->no;
    }


    public function setHauteur(float $hauteur): self
    {
        $this->hauteur = $hauteur;
        return $this;
    }

    public function setLongueur(float $longueur): self
    {
        $this->longueur = $longueur;
        return $this;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;
        return $this;
    }

    public function setHaie(?Haie $haie): self
    {
        $this->haie = $haie;
        return $this;
    }
}

