<?php

namespace App\Repository;

use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etudiant>
 *
 * @method Etudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiant[]    findAll()
 * @method Etudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function add(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Etudiant[] Returns an array of Etudiant objects
     */
    public function listeEtudiantDuneClasse($id, $annee): array
    {
        return $this->createQueryBuilder('e')
            ->select('c.id as id, e as etud')
            ->join('e.classe', 'c')
            ->join('e.anneeScolaire', 'a')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->andwhere('a.anneeScolaire = :annee')
            ->setParameter('annee', $annee)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function listeEtudiantDuneClasseEtAnnee($id): array
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.classe', 'c')
            ->join('e.anneeScolaire', 'a')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->andwhere('a.active = :active')
            ->setParameter('active', true)
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function classeAReinscrire($classe): array
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.classe', 'c')
            ->join('e.anneeScolaire', 'a')
            ->where('c.codeClasse = :codeClasse')
            ->setParameter('codeClasse', $classe)
            ->andwhere('a.active = :active')
            ->setParameter('active', true)
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    
    public function listeEtudiantDuneAnnee(): array
    {
        return $this->createQueryBuilder('e')
            ->select('a.id as id, e as etud')
            ->join('e.anneeScolaire', 'a')
            ->andwhere('a.active = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult()
        ;
    }

    public function listeEtudiantDuneAnneeActive($limit): array
    {
        return $this->createQueryBuilder('e')
            ->select('a.id as id, e as etud')
            ->join('e.anneeScolaire', 'a')
            ->andwhere('a.active = :active')
            ->setParameter('active', true)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function EtudiantDuneClasse($id): array
    {
        return $this->createQueryBuilder('e')
            ->select('c.id as id, e as etud')
            ->join('e.classe', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function listeDeNoteDuneClasse($id): array
    {
        return $this->createQueryBuilder('e')
            ->select('n.id as id, e as etud')
            ->join('e.classe', 'n')
            ->where('n.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function matricule($id): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.matricule as mat')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByYear($annee): array
    {
        return $this->createQueryBuilder('e')
            ->select('a.id as id, e as etud')
            ->join('e.anneeScolaire', 'a')
            ->Where('a.AnneeScolaire = :annee')
            ->setParameter('annee', $annee)
            ->getQuery()
            ->getResult()
        ;
    
    }

    public function findAllStudents(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->setMaxResults(7)
            ->getQuery()
            ->getResult()
        ;
    
    }


    public function findForPaginationEtudiant(): Query
    {
        $qb = $this->createQueryBuilder('e')
                ->orderBy('e.id', 'DESC');
        return $qb->getQuery();
    }

    public function findBySearch($search)
    {
        return $this->createQueryBuilder('e')
                ->join('e.classe', 'c')
                ->andWhere('e.nom LIKE :val
                OR e.prenoms LIKE :val
                OR e.matricule LIKE :val
                OR e.examenPrepare LIKE :val
                OR e.filieres LIKE :val
                OR c.denomination LIKE :val
                ')
                ->setParameter('val', $search)
                ->orderBy('e.id', 'DESC')
                ->getQuery()
                ->getResult();
       
    }


    public function searchStudents($criteria)
    {

            return $this->createQueryBuilder('e')
            ->select('c.id as id, e as etud')
            ->join('e.classe', 'c')
            ->join('e.anneeScolaire', 'a')
            ->where('c.codeClasse = :codeClasse')
            ->setParameter('codeClasse', $criteria['codeClasse']->getCodeClasse())
            ->andwhere('a.AnneeScolaire = :AnneeScolaire')
            ->setParameter('AnneeScolaire', $criteria['AnneeScolaire']->getAnneeScolaire())
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult()
    ;
    }


}
