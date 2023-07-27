<?php

namespace App\Repository;

use App\Entity\Matieres;
use App\Entity\Noter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Noter>
 *
 * @method Noter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Noter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Noter[]    findAll()
 * @method Noter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Noter::class);
    }

    public function add(Noter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Noter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Noter[] Returns an array of Noter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function listeNote($semestre,$annee,$classe): array
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->andWhere('n.semestre = :semestre')
            ->setParameter('semestre', $semestre)
            ->andWhere('n.Annee = :Annee')
            ->setParameter('Annee', $annee)
            ->andWhere('n.Classes = :Classes')
            ->setParameter('Classes', $classe)
            ->getQuery()
            ->getResult()
        ;
    }

    public function NoteParEtudiant($matricule,$semestre,$denomination,$annee,$classe): array
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->join('n.matiere', 'm')
            ->join('n.matricules', 'e')
            ->join('m.TypeMatiere', 't')
            ->andWhere('t.denomination = :denomination')
            ->setParameter('denomination', $denomination)
            ->andWhere('e.matricule = :matricule')
            ->setParameter('matricule', $matricule)
            ->andWhere('n.semestre = :semestre')
            ->setParameter('semestre', $semestre)
            ->andWhere('n.Annee = :Annee')
            ->setParameter('Annee', $annee)
            ->andWhere('n.Classes = :Classes')
            ->setParameter('Classes', $classe)
            ->getQuery()
            ->getResult()
        ;
    }

    public function NoteParTypeMatiere($semestre,$denomination,$annee,$classe): array
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->join('n.matiere', 'm')
            ->join('m.TypeMatiere', 't')
            ->andWhere('t.denomination = :denomination')
            ->setParameter('denomination', $denomination)
            ->andWhere('n.semestre = :semestre')
            ->setParameter('semestre', $semestre)
            ->andWhere('n.Annee = :Annee')
            ->setParameter('Annee', $annee)
            ->andWhere('n.Classes = :Classes')
            ->setParameter('Classes', $classe)
            ->getQuery()
            ->getResult()
        ;
    }


    public function NoteParMatiere($semestre,$denomination,$annee,$classe): array
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->join('n.matiere', 'm')
            ->andWhere('m.denomination = :denomination')
            ->setParameter('denomination', $denomination)
            ->andWhere('n.semestre = :semestre')
            ->setParameter('semestre', $semestre)
            ->andWhere('n.Annee = :Annee')
            ->setParameter('Annee', $annee)
            ->andWhere('n.Classes = :Classes')
            ->setParameter('Classes', $classe)
            ->getQuery()
            ->getResult()
        ;
    }


    public function editNote($matricule, $matiere): ?Noter
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.etudiants = :etudiants')
            ->setParameter('etudiants', $matricule)
            ->andWhere('n.matieres = :matieres')
            ->setParameter('matieres', $matiere)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    public function listeNoteParEtudiant($matricule, $matiere,$semestre)
    {
        return $this->createQueryBuilder('n')
            ->join('n.matiere', 'm')
            ->join('n.matricules', 'e')
            ->andWhere('e.matricule = :matricule')
            ->setParameter('matricule', $matricule)
            ->andWhere('m.denomination = :denomination')
            ->setParameter('denomination', $matiere)
            ->andWhere('n.semestre = :semestre')
            ->setParameter('semestre', $semestre)
            ->getQuery()
            ->getSingleResult()
        ;
    }



    public function typeMatiere($denomination)
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->join('n.matiere', 'm')
            ->join('m.TypeMatiere', 't')
            ->andWhere('t.denomination = :denomination')
            ->setParameter('denomination', $denomination)
            ->getQuery()
            ->getResult()
        ;
    }


    public function rangParEtudiant()
    {
        return $this->createQueryBuilder('n')
            ->select('AVG(n.moyenne) as moy')
            ->groupBy('n.matricules')
            ->getQuery()
            ->getResult()
        ;
    }
    

   
}
