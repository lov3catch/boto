<?php

namespace App\Repository;

use App\Entity\ModeratorGroupOwners;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ModeratorGroupOwners|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorGroupOwners|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorGroupOwners[]    findAll()
 * @method ModeratorGroupOwners[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorGroupOwnersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ModeratorGroupOwners::class);
    }

    // /**
    //  * @return ModeratorGroupOwners[] Returns an array of ModeratorGroupOwners objects
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
    public function findOneBySomeField($value): ?ModeratorGroupOwners
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
