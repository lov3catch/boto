<?php

namespace App\Repository;

use App\Entity\ChannelActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChannelActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChannelActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChannelActivity[]    findAll()
 * @method ChannelActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChannelActivityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChannelActivity::class);
    }

    // /**
    //  * @return ChannelActivity[] Returns an array of ChannelActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChannelActivity
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
