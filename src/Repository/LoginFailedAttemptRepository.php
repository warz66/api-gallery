<?php

namespace App\Repository;

use App\Entity\LoginFailedAttempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoginFailedAttempt|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginFailedAttempt|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginFailedAttempt[]    findAll()
 * @method LoginFailedAttempt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginFailedAttemptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginFailedAttempt::class);
    }

    public function countRecentLoginFailedAttempts(string $ipAddress, $delayMinutes): int
    {
        $timeAgo = new \DateTimeImmutable(sprintf('-%d minutes', $delayMinutes));

        return $this->createQueryBuilder('la')
            ->select('COUNT(la)')
            ->where('la.date >= :date')
            ->andWhere('la.ipAddress = :ipAddress')
            ->setParameters([
                'date' => $timeAgo,
                'ipAddress' => $ipAddress
            ])
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function cleanLoginFailedAttempts($delayMinutes)
    {
        $timeAgo = new \DateTimeImmutable(sprintf('-%d minutes', $delayMinutes));

        $this->createQueryBuilder('la')
        ->delete()
        ->where('la.date < :date')
            ->setParameters([
                'date' => $timeAgo,
            ])
        ->getQuery()
        ->execute();        
    }

    // /**
    //  * @return LoginFailedAttempt[] Returns an array of LoginFailedAttempt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LoginFailedAttempt
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
