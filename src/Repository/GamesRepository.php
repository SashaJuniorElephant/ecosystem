<?php

namespace App\Repository;

use App\Entity\Games;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Games|null find($id, $lockMode = null, $lockVersion = null)
 * @method Games|null findOneBy(array $criteria, array $orderBy = null)
 * @method Games[]    findAll()
 * @method Games[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Games::class);
    }

    public function findById($id): ?Games
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Games[] Returns an array of Games objects
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
    public function findOneBySomeField($value): ?Games
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
