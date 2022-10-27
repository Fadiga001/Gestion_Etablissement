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

    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: Notes::class)]
    private Collection $note;


    public function __construct()
    {
        $this->classe = new ArrayCollection();
        $this->note = new ArrayCollection();
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

    /**
     * @return Collection<int, Notes>
     */
    public function getNote(): Collection
    {
        return $this->note;
    }

    public function addNote(Notes $note): self
    {
        if (!$this->note->contains($note)) {
            $this->note->add($note);
            $note->setMatiere($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): self
    {
        if ($this->note->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getMatiere() === $this) {
                $note->setMatiere(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->denomination;
    }
   
}
