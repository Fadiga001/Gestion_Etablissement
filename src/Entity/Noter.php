<?php

namespace App\Entity;

use App\Repository\NoterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoterRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Noter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $semestre = null;

    #[ORM\Column(nullable: true)]
    private ?float $noteEtudiant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateJour = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Classes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etudiants = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prof = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matieres = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeEvaluation = null;

    #[ORM\Column(nullable: true)]
    private ?float $moyenne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Annee = null;

    #[ORM\Column(nullable: true)]
    private ?float $noteClasse = null;

    #[ORM\Column(nullable: true)]
    private ?float $notePartiel = null;

    #[ORM\ManyToOne(inversedBy: 'noters')]
    private ?Matieres $matiere = null;



    #[ORM\PrePersist]
    public function onPrePersist(){
        $this->dateJour = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSemestre(): ?string
    {
        return $this->semestre;
    }

    public function setSemestre(string $semestre): self
    {
        $this->semestre = $semestre;

        return $this;
    }

    public function getNoteEtudiant(): ?float
    {
        return $this->noteEtudiant;
    }

    public function setNoteEtudiant(float $noteEtudiant): self
    {
        $this->noteEtudiant = $noteEtudiant;

        return $this;
    }

    public function getDateJour(): ?\DateTimeInterface
    {
        return $this->dateJour;
    }

    public function setDateJour(\DateTimeInterface $dateJour): self
    {
        $this->dateJour = $dateJour;

        return $this;
    }

    public function getClasses(): ?string
    {
        return $this->Classes;
    }

    public function setClasses(?string $Classes): self
    {
        $this->Classes = $Classes;

        return $this;
    }

    public function getEtudiants(): ?string
    {
        return $this->etudiants;
    }

    public function setEtudiants(?string $etudiants): self
    {
        $this->etudiants = $etudiants;

        return $this;
    }

    public function getProf(): ?string
    {
        return $this->prof;
    }

    public function setProf(?string $prof): self
    {
        $this->prof = $prof;

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

    public function getTypeEvaluation(): ?string
    {
        return $this->typeEvaluation;
    }

    public function setTypeEvaluation(?string $typeEvaluation): self
    {
        $this->typeEvaluation = $typeEvaluation;

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

    public function getAnnee(): ?string
    {
        return $this->Annee;
    }

    public function setAnnee(string $Annee): self
    {
        $this->Annee = $Annee;

        return $this;
    }

    public function getNoteClasse(): ?float
    {
        return $this->noteClasse;
    }

    public function setNoteClasse(?float $noteClasse): self
    {
        $this->noteClasse = $noteClasse;

        return $this;
    }

    public function getNotePartiel(): ?float
    {
        return $this->notePartiel;
    }

    public function setNotePartiel(?float $notePartiel): self
    {
        $this->notePartiel = $notePartiel;

        return $this;
    }

    public function getMatiere(): ?Matieres
    {
        return $this->matiere;
    }

    public function setMatiere(?Matieres $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }


   
}
