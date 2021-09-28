<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="etats")
     */
    private $sortie;

    public function __construct()
    {
        $this->sortie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortie(): Collection
    {
        return $this->sortie;
    }

    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sortie->contains($sortie)) {
            $this->sortie[] = $sortie;
            $sortie->setEtats($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): self
    {
        if ($this->sortie->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getEtats() === $this) {
                $sortie->setEtats(null);
            }
        }

        return $this;
    }
    public function __toString():string
    {
        return $this->libelle;
    }
}
