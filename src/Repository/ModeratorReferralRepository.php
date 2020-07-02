<?php

namespace App\Repository;

use App\Entity\ModeratorReferral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ModeratorReferral|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorReferral|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorReferral[]    findAll()
 * @method ModeratorReferral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorReferralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeratorReferral::class);
    }

    // /**
    //  * @return ModeratorReferral[] Returns an array of ModeratorReferral objects
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
    public function findOneBySomeField($value): ?ModeratorReferral
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getOrCreate(array $options, array $defaults): ModeratorReferral
    {
        $entity = $this->findOneBy($options);

        if ($entity instanceof ModeratorReferral) return $entity;

        $moderatorReferral = new ModeratorReferral();
        $moderatorReferral->setUserId($defaults['user_id']);
        $moderatorReferral->setGroupId($defaults['group_id']);
        $moderatorReferral->setReferralId($defaults['referral_id']);
        $moderatorReferral->setCreatedAt(new \DateTimeImmutable());

        $this->save($moderatorReferral);

        return $this->getOrCreate($defaults, $defaults);
    }

    public function save(ModeratorReferral $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}
