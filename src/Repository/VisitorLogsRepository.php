<?php

namespace App\Repository;

use App\Entity\VisitorLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VisitorLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisitorLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisitorLogs[]    findAll()
 * @method VisitorLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitorLogsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VisitorLogs::class);
    }

    // /**
    //  * @return VisitorLogs[] Returns an array of VisitorLogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VisitorLogs
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
