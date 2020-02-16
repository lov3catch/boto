<?php

namespace App\Repository;

use App\Entity\ModeratorBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ModeratorBlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorBlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorBlock[]    findAll()
 * @method ModeratorBlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorBlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeratorBlock::class);
    }

    // /**
    //  * @return ModeratorBlock[] Returns an array of ModeratorBlock objects
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
    public function findOneBySomeField($value): ?ModeratorBlock
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
