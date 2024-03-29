<?php

namespace App\Entity;

use App\Repository\MatieresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as assert;

#[ORM\Entity(repositoryClass: MatieresRepository::class)]
#[UniqueEntity(fields: ['codeMatiere'], message: 'Ce code existe déjà!')]
class Matieres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $codeMatiere = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $denomination = null;

    #[ORM\ManyToOne(inversedBy: 'matieres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Professeurs $prof = null;

    #[ORM\ManyToOne(inversedBy: 'matieres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeMatieres $TypeMatiere = null;

    #[ORM\ManyToMany(targetEntity: Classe::class, inversedBy: 'matieres')]
    private Collection $classe;

    #[ORM\Column(nullable: true)]
    private ?int $coefficient = null;

    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: Noter::class)]
    private Collection $noters;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFile = null;


    public function __construct()
    {
        $this->classe = new ArrayCollection();
        $this->noters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeMatiere(): ?string
    {
        return $this->codeMatiere;
    }

    public function setCodeMatiere(string $codeMatiere): self
    {
        $this->codeMatiere = $codeMatiere;

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

    public function getProf(): ?Professeurs
    {
        return $this->prof;
    }

    public function setProf(?Professeurs $prof): self
    {
        $this->prof = $prof;

        return $this;
    }

    public function getTypeMatiere(): ?TypeMatieres
    {
        return $this->TypeMatiere;
    }

    public function setTypeMatiere(?TypeMatieres $TypeMatiere): self
    {
        $this->TypeMatiere = $TypeMatiere;

        return $this;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClasse(): Collection
    {
        return $this->classe;
    }

    public function addClasse(Classe $classe): self
    {
        if (!$this->classe->contains($classe)) {
            $this->classe->add($classe);
        }

        return $this;
    }

    public function removeClasse(Classe $classe): self
    {
        $this->classe->removeElement($classe);

        return $this;
    }

   
    public function __toString()
    {
        return $this->denomination;
    }

    public function getCoefficient(): ?int
    {
        return $this->coefficient;
    }

    public function setCoefficient(?int $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * @return Collection<int, Noter>
     */
    public function getNoters(): Collection
    {
        return $this->noters;
    }

    public function addNoter(Noter $noter): self
    {
        if (!$this->noters->contains($noter)) {
            $this->noters->add($noter);
            $noter->setMatiere($this);
        }

        return $this;
    }

    public function removeNoter(Noter $noter): self
    {
        if ($this->noters->removeElement($noter)) {
            // set the owning side to null (unless already changed)
            if ($noter->getMatiere() === $this) {
                $noter->setMatiere(null);
            }
        }

        return $this;
    }

    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }

    public function setImageFile(?string $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }

   
   
}
