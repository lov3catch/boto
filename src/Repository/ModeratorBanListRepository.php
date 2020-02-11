<?php

namespace App\Repository;

use App\Entity\ModeratorBanList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ModeratorBanList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorBanList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorBanList[]    findAll()
 * @method ModeratorBanList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorBanListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeratorBanList::class);
    }

    // /**
    //  * @return ModeratorBanList[] Returns an array of ModeratorBanList objects
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
    public function findOneBySomeField($value): ?ModeratorBanList
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
