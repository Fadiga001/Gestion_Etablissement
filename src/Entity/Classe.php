<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as assert;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
#[UniqueEntity(fields: ['codeClasse'], message: 'Ce code de la classe existe déjà!')]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $codeClasse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $denomination = null;

    #[ORM\ManyToOne(inversedBy: 'classes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filiere $filiere = null;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Etudiant::class)]
    private Collection $etudiants;

    #[ORM\ManyToMany(targetEntity: Matieres::class, mappedBy: 'classe')]
    private Collection $matieres;






    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->matieres = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeClasse(): ?string
    {
        return $this->codeClasse;
    }

    public function setCodeClasse(string $codeClasse): self
    {
        $this->codeClasse = $codeClasse;

        return $this;
    }

    public function getDenomination(): ?string
    {
        return $this->denomination;
    }

    public function setDenomination(string $denomination): self
    {
        $this->denomination = $denomination;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
            $etudiant->setClasse($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getClasse() === $this) {
                $etudiant->setClasse(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->denomination;
    }

    /**
     * @return Collection<int, Matieres>
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matieres $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres->add($matiere);
            $matiere->addClasse($this);
        }

        return $this;
    }

    public function removeMatiere(Matieres $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) {
            $matiere->removeClasse($this);
        }

        return $this;
    }

    
   
}
