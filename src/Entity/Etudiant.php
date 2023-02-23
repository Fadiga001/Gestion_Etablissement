<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EtudiantRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as assert;


#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
#[UniqueEntity(fields: ['matricule'], message: 'Ce matricule est déjà utilisé par un autre et doit etre unique!')]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min:17, minMessage:"Le matricule doit faire 17 caractères",
        max:17, maxMessage:"Le matricule ne doit pas depasser les 17 caractères"
    )]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $prenoms = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $lieuNaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $paysNaissance = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $sexe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nationalite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etablissementDeProvenance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $personneAContacter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseDePersonneAContacter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephoneDePersonneAContacter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'etudiants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnneeAcademique $anneeScolaire = null;

    #[ORM\ManyToOne(inversedBy: 'etudiants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classe $classe = null;



     #[ORM\Column(nullable: true)]
     private ?bool $reinscrire = null;

     #[ORM\Column(nullable: true)]
     private ?float $moyenne = null;

     #[ORM\Column(length: 255, nullable: true)]
     private ?string $imageFile = null;

     #[ORM\OneToMany(mappedBy: 'matricules', targetEntity: Noter::class)]
     private Collection $noters;

     public function __construct()
     {
         $this->noters = new ArrayCollection();
     }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(string $prenoms): self
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(string $lieuNaissance): self
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    public function getPaysNaissance(): ?string
    {
        return $this->paysNaissance;
    }

    public function setPaysNaissance(string $paysNaissance): self
    {
        $this->paysNaissance = $paysNaissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getEtablissementDeProvenance(): ?string
    {
        return $this->etablissementDeProvenance;
    }

    public function setEtablissementDeProvenance(?string $etablissementDeProvenance): self
    {
        $this->etablissementDeProvenance = $etablissementDeProvenance;

        return $this;
    }

    public function getPersonneAContacter(): ?string
    {
        return $this->personneAContacter;
    }

    public function setPersonneAContacter(string $personneAContacter): self
    {
        $this->personneAContacter = $personneAContacter;

        return $this;
    }

    public function getAdresseDePersonneAContacter(): ?string
    {
        return $this->adresseDePersonneAContacter;
    }

    public function setAdresseDePersonneAContacter(string $adresseDePersonneAContacter): self
    {
        $this->adresseDePersonneAContacter = $adresseDePersonneAContacter;

        return $this;
    }

    public function getTelephoneDePersonneAContacter(): ?string
    {
        return $this->telephoneDePersonneAContacter;
    }

    public function setTelephoneDePersonneAContacter(string $telephoneDePersonneAContacter): self
    {
        $this->telephoneDePersonneAContacter = $telephoneDePersonneAContacter;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }


    public function getAnneeScolaire(): ?AnneeAcademique
    {
        return $this->anneeScolaire;
    }

    public function setAnneeScolaire(?AnneeAcademique $anneeScolaire): self
    {
        $this->anneeScolaire = $anneeScolaire;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    

    
    #[ORM\PrePersist]
    public function onPrePersist(){
        $this->dateInscription = new \DateTime();
    }

    public function __toString()
    {
        return $this->matricule;
    }

    public function isReinscrire(): ?bool
    {
        return $this->reinscrire;
    }

    public function setReinscrire(?bool $reinscrire): self
    {
        $this->reinscrire = $reinscrire;

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

    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }

    public function setImageFile(?string $imageFile): self
    {
        $this->imageFile = $imageFile;

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
            $noter->setMatricules($this);
        }

        return $this;
    }

    public function removeNoter(Noter $noter): self
    {
        if ($this->noters->removeElement($noter)) {
            // set the owning side to null (unless already changed)
            if ($noter->getMatricules() === $this) {
                $noter->setMatricules(null);
            }
        }

        return $this;
    }


    
  
}
