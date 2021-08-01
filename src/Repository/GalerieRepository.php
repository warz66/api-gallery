<?php

namespace App\Repository;

use App\Entity\Galerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Galerie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Galerie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Galerie[]    findAll()
 * @method Galerie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Galerie::class);
    }

    public function findGaleries($trash) {
        return $this->createQueryBuilder('g')
                ->Where('g.trash = :trash')
                ->setParameter('trash', $trash)
                ->orderBy('g.id','DESC')
                ->getQuery()
                ->getResult();
    }

    public function findAllStatutOk() {
        return $this->createQueryBuilder('g')
                ->Where('g.statut = 1')
                ->andWhere('g.trash = 0')
                ->orderBy('g.id','DESC')
                ->getQuery()
                ->getResult();
    }

    // /**
    //  * @return Galerie[] Returns an array of Galerie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Galerie
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
