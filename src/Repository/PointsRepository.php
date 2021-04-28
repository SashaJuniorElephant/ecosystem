<?php

namespace App\Repository;

use App\Entity\Points;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Points|null find($id, $lockMode = null, $lockVersion = null)
 * @method Points|null findOneBy(array $criteria, array $orderBy = null)
 * @method Points[]    findAll()
 * @method Points[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Points::class);
    }

    // TODO: автоматически делается пустой запрос к БД
    public function findByGameId($gameID)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.game = :gameID')
            ->setParameter('gameID', $gameID)
            ->orderBy('p.x, p.y', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    // Загрузка вместе с юнитами
//    public function findByGameId($gameID)
//    {
//        return $this->createQueryBuilder('p')
//            ->select('p', 'u')
//            ->leftJoin('p.units', 'u')
//            ->andWhere('p.game = :gameID')
//            ->setParameter('gameID', $gameID)
//            ->orderBy('p.x, p.y', 'ASC')
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    // /**
    //  * @return Points[] Returns an array of Points objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Points
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
