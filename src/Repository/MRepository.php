<?php

namespace App\Repository;

use App\Entity\M;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method M|null find($id, $lockMode = null, $lockVersion = null)
 * @method M|null findOneBy(array $criteria, array $orderBy = null)
 * @method M[]    findAll()
 * @method M[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, M::class);
    }

    // /**
    //  * @return M[] Returns an array of M objects
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
    public function findOneBySomeField($value): ?M
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
