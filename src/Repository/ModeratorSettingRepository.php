<?php

namespace App\Repository;

use App\Entity\ModeratorSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ModeratorSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorSetting[]    findAll()
 * @method ModeratorSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeratorSetting::class);
    }

    // /**
    //  * @return ModeratorSetting[] Returns an array of ModeratorSetting objects
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
    public function findOneBySomeField($value): ?ModeratorSetting
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
