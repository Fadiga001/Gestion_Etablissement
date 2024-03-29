<?php

namespace App\Repository;

use App\Entity\Matieres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matieres>
 *
 * @method Matieres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matieres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matieres[]    findAll()
 * @method Matieres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatieresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matieres::class);
    }

    public function add(Matieres $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Matieres $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Matieres[] Returns an array of Matieres objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Matieres
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

        public function listeMatieresParClasse($id){
            return $this->createQueryBuilder('m')
                ->join('m.classe', 'c')
                ->Where('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
        }

        public function MatieresParClasse($classe){
            return $this->createQueryBuilder('m')
                ->join('m.classe', 'c')
                ->Where('c.codeClasse = :codeClasse')
                ->setParameter('codeClasse', $classe)
                ->getQuery()
                ->getResult();
        }

       

}
