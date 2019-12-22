<?php

namespace App\Repository;

use App\Entity\ModeratorPartnersProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ModeratorPartnersProgram|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorPartnersProgram|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorPartnersProgram[]    findAll()
 * @method ModeratorPartnersProgram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorPartnersProgramRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ModeratorPartnersProgram::class);
    }

    // /**
    //  * @return ModeratorPartnersProgram[] Returns an array of ModeratorPartnersProgram objects
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
    public function findOneBySomeField($value): ?ModeratorPartnersProgram
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
