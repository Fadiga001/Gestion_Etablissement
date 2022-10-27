<?php

namespace App\Entity;

use App\Repository\FiliereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as assert;

#[ORM\Entity(repositoryClass: FiliereRepository::class)]
#[UniqueEntity(fields: ['codeFiliere'], message: 'Ce code existe déjà!')]
class Filiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $denomination = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $codeFiliere = null;

    #[ORM\OneToMany(mappedBy: 'filiere', targetEntity: Classe::class)]
    private Collection $classes;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCodeFiliere(): ?string
    {
        return $this->codeFiliere;
    }

    public function setCodeFiliere(string $codeFiliere): self
    {
        $this->codeFiliere = $codeFiliere;

        return $this;
    }

   

    public function __toString()
    {
        return $this->denomination;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes->add($class);
            $class->setFiliere($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getFiliere() === $this) {
                $class->setFiliere(null);
            }
        }

        return $this;
    }
}
