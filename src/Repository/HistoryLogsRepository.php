<?php

namespace App\Repository;

use App\Entity\HistoryLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistoryLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoryLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoryLogs[]    findAll()
 * @method HistoryLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryLogsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistoryLogs::class);
    }

    // /**
    //  * @return HistoryLogs[] Returns an array of HistoryLogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoryLogs
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
