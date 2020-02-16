<?php

namespace App\Repository;

use App\Entity\ModeratorGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ModeratorGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorGroup[]    findAll()
 * @method ModeratorGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeratorGroup::class);
    }

    // /**
    //  * @return ModeratorGroup[] Returns an array of ModeratorGroup objects
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
    public function findOneBySomeField($value): ?ModeratorGroup
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getOrCreate(array $options, array $defaults): ModeratorGroup
    {
        $entity = $this->findOneBy($options);

        if ($entity instanceof ModeratorGroup) return $entity;

        $moderatorGroup = new ModeratorGroup();
        $moderatorGroup->setGroupId($defaults['group_id']);
        $moderatorGroup->setGroupTitle($defaults['group_title']);
        $moderatorGroup->setGroupUsername($defaults['group_username']);
        $moderatorGroup->setGroupType($defaults['group_type']);
        $moderatorGroup->setCreatedAt(new \DateTimeImmutable());
        $moderatorGroup->setUpdatedAt(new \DateTimeImmutable());

        $this->save($moderatorGroup);

        return $this->getOrCreate($defaults, $defaults);
    }

    public function save(ModeratorGroup $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}
