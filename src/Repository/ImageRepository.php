<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function getImagesByOrdreTableauAsc($galerieId) {
        return new ArrayCollection($this->createQueryBuilder('i')
                    ->where('i.galerie = :galerieId')
                    ->addSelect('CASE WHEN i.ordre IS NULL THEN 1 ELSE 0 END AS HIDDEN ordre_is_null')
                    ->setParameter('galerieId', $galerieId)
                    ->addOrderBy('ordre_is_null', 'ASC')
                    ->addOrderBy('i.ordre', 'ASC')
                    ->getQuery()
                    ->getResult());
    }

    public function getImagesByOrdreTableauDesc($galerieId) {
        return new ArrayCollection($this->createQueryBuilder('i')
                    ->where('i.galerie = :galerieId')
                    ->addSelect('CASE WHEN i.ordre IS NULL THEN 1 ELSE 0 END AS HIDDEN ordre_is_null')
                    ->setParameter('galerieId', $galerieId)
                    ->addOrderBy('ordre_is_null', 'DESC')
                    ->addOrderBy('i.ordre', 'DESC')
                    ->getQuery()
                    ->getResult());
    }

    public function findSameImage($id, $url) {
        return $this->createQueryBuilder('i')
                    ->Where('i.publication = :id')
                    ->setParameter('id', $id)
                    ->andWhere('i.url = :url')
                    ->setParameter('url', $url)
                    ->getQuery()
                    ->getOneOrNullResult();                  
    }

    public function findImageOnPublication($id) {
        return $this->createQueryBuilder('i')
                    ->join('i.publication', 'a')
                    ->Where('a.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getResult();                  
    }

    public function findSameImageInOtherPublication($id, $url) {
        return $this->createQueryBuilder('i')
                    ->join('i.publication', 'a')
                    ->Where('a.id != :id')
                    ->setParameter('id', $id)
                    ->andWhere('i.url = :url')
                    ->setParameter('url', $url)
                    ->setMaxResults(1) // Permet de retourner qu'un seul resultat, sinon fait bugger le getOneOrNullResult
                    ->getQuery()
                    ->getOneOrNullResult();                 
    }

    // /**
    //  * @return Image[] Returns an array of Image objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Image
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
