<?php

namespace App\Repository;

use App\Entity\Notes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notes>
 *
 * @method Notes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notes[]    findAll()
 * @method Notes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notes::class);
    }

    public function add(Notes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Notes[] Returns an array of Notes objects
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

   public function noteEtudiantMG($mat, $semestre, $typeMat): array
   {
        return $this->createQueryBuilder('n')
                    ->select(' n as note')
                    ->join('n.matiere', 'matiere')
                    ->join('matiere.TypeMatiere', 'typeMat')
                    ->where('n.etudiant = :mat')
                    ->setParameter('mat', $mat)
                    ->andWhere('n.semestre = :semestre')
                    ->setParameter('semestre', $semestre)
                    ->andWhere('typeMat.denomination = :denomination')
                    ->setParameter(':denomination', $typeMat)
                    ->getQuery()
                    ->getResult()
                    ;
  }

   public function noteEtudiantMP($mat, $semestre, $typeMat): array
   {
        return $this->createQueryBuilder('n')
                    ->select(' n as note')
                    ->join('n.matiere', 'matiere')
                    ->join('matiere.TypeMatiere', 'typeMat')
                    ->where('n.etudiant = :mat')
                    ->setParameter('mat', $mat)
                    ->andWhere('n.semestre = :semestre')
                    ->setParameter('semestre', $semestre)
                    ->andWhere('typeMat.denomination = :denomination')
                    ->setParameter(':denomination', $typeMat)
                    ->getQuery()
                    ->getResult()
                    ;
  }

   public function noteEtudiantMA($mat, $semestre, $typeMat): array
   {
        return $this->createQueryBuilder('n')
                    ->select(' n as note, SUM(n.coeffient) as sumCoef')
                    ->join('n.matiere', 'matiere')
                    ->join('matiere.TypeMatiere', 'typeMat')
                    ->where('n.etudiant = :mat')
                    ->setParameter('mat', $mat)
                    ->andWhere('n.semestre = :semestre')
                    ->setParameter('semestre', $semestre)
                    ->andWhere('typeMat.denomination = :denomination')
                    ->setParameter(':denomination', $typeMat)
                    ->getQuery()
                    ->getResult()
                    ;
  }

   public function coeffMG($mat, $semestre, $typeMat): array
   {
        return $this->createQueryBuilder('n')
                    ->select('SUM(n.coefficient) as sumCoef')
                    ->join('n.matiere', 'matiere')
                    ->join('matiere.TypeMatiere', 'typeMat')
                    ->where('n.etudiant = :mat')
                    ->setParameter('mat', $mat)
                    ->andWhere('n.semestre = :semestre')
                    ->setParameter('semestre', $semestre)
                    ->andWhere('typeMat.denomination = :denomination')
                    ->setParameter(':denomination', $typeMat)
                    ->getQuery()
                    ->getResult()
                    ;
  }

}
