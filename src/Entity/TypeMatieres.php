<?php

namespace App\Entity;

use App\Repository\TypeMatieresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as assert;

#[ORM\Entity(repositoryClass: TypeMatieresRepository::class)]
#[UniqueEntity(fields: ['codeTypeMatiere'], message: 'Ce code existe déjà!')]
class TypeMatieres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $codeTypeMatiere = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $denomination = null;

    #[ORM\OneToMany(mappedBy: 'TypeMatiere', targetEntity: Matieres::class)]
    private Collection $matieres;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeTypeMatiere(): ?string
    {
        return $this->codeTypeMatiere;
    }

    public function setCodeTypeMatiere(string $codeTypeMatiere): self
    {
        $this->codeTypeMatiere = $codeTypeMatiere;

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
            $matiere->setTypeMatiere($this);
        }

        return $this;
    }

    public function removeMatiere(Matieres $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getTypeMatiere() === $this) {
                $matiere->setTypeMatiere(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->denomination;
    }
}
