<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $no = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(name: "client_id", referencedColumnName: "no")]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: Tailler::class)]
    private Collection $taillers;

    public function __construct()
    {
        $this->taillers = new ArrayCollection();
    }

    public function getNo(): ?int
    {
        return $this->no;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, Tailler>
     */
    public function getTaillers(): Collection
    {
        return $this->taillers;
    }

    public function addTailler(Tailler $tailler): self
    {
        if (!$this->taillers->contains($tailler)) {
            $this->taillers->add($tailler);
            $tailler->setDevis($this);
        }

        return $this;
    }

    public function removeTailler(Tailler $tailler): self
    {
        if ($this->taillers->removeElement($tailler)) {
            if ($tailler->getDevis() === $this) {
                $tailler->setDevis(null);
            }
        }

        return $this;
    }
}
