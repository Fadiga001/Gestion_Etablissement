<?php

namespace App\Repository;

use App\Entity\AnneeAcademique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnneeAcademique>
 *
 * @method AnneeAcademique|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnneeAcademique|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnneeAcademique[]    findAll()
 * @method AnneeAcademique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnneeAcademiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnneeAcademique::class);
    }

    public function add(AnneeAcademique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AnneeAcademique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AnneeAcademique[] Returns an array of AnneeAcademique objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnneeAcademique
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByYear($annee): array
    {
        return $this->createQueryBuilder('a')
                    ->select('a.AnneeScolaire as annee')
                    ->where('a.id = :id')
                    ->setParameter('id', $annee)
                    ->getQuery()
                    ->getResult()
        ;
    }
    
    public function Year($annee)
    {
        return $this->createQueryBuilder('a')
                    ->select('a.AnneeScolaire as annee')
                    ->where('a.id = :id')
                    ->setParameter('id', $annee)
                    ->getQuery()
                    ->getSingleScalarResult()
        ;
    }

    public function actifYear()
    {
        return $this->createQueryBuilder('a')
                    ->select('a')
                    ->where('a.active = :active')
                    ->setParameter('active', true)
                    ->getQuery()
                    ->getScalarResult()
        ;
    }

    

}
