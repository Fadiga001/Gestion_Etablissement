<?php

namespace App\Entity;

use App\Repository\MoyenneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoyenneRepository::class)]
class Moyenne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $semestre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $classes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matieres = null;

    #[ORM\Column(nullable: true)]
    private ?float $moyenne = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSemestre(): ?string
    {
        return $this->semestre;
    }

    public function setSemestre(?string $semestre): self
    {
        $this->semestre = $semestre;

        return $this;
    }

    public function getClasses(): ?string
    {
        return $this->classes;
    }

    public function setClasses(?string $classes): self
    {
        $this->classes = $classes;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getMatieres(): ?string
    {
        return $this->matieres;
    }

    public function setMatieres(?string $matieres): self
    {
        $this->matieres = $matieres;

        return $this;
    }

    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }

    public function setMoyenne(?float $moyenne): self
    {
        $this->moyenne = $moyenne;

        return $this;
    }
}
