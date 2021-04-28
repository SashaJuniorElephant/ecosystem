<?php

namespace App\Repository;

use App\Entity\MapState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MapState|null find($id, $lockMode = null, $lockVersion = null)
 * @method MapState|null findOneBy(array $criteria, array $orderBy = null)
 * @method MapState[]    findAll()
 * @method MapState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MapStateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MapState::class);
    }

    public function findLastStateByGameID($gameID): ?MapState
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.game = :gameID')
            ->setParameter('gameID', $gameID)
            ->orderBy('m.step', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByGameId($gameID)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.game = :gameID')
            ->setParameter('gameID', $gameID)
            ->orderBy('m.step', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return MapState[] Returns an array of MapState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MapState
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
