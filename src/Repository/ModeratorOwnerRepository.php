<?php

namespace App\Repository;

use App\Entity\ModeratorOwner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ModeratorOwner|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorOwner|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorOwner[]    findAll()
 * @method ModeratorOwner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorOwnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeratorOwner::class);
    }

    // /**
    //  * @return ModeratorOwner[] Returns an array of ModeratorOwner objects
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
    public function findOneBySomeField($value): ?ModeratorOwner
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getOrCreate(int $userId, int $groupId): ModeratorOwner
    {
        // ищем только по айди группы, тк может быть только одна запись для группы и потом просто надо изменить ID владельца
        $entity = $this->findOneBy(['group_id' => $groupId]);

        if ($entity instanceof ModeratorOwner) return $entity;

        $moderatorOwner = new ModeratorOwner();
        $moderatorOwner->setUserId($userId);
        $moderatorOwner->setGroupId($groupId);
        $moderatorOwner->setIsActive(true);
        $moderatorOwner->setCreatedAt(new \DateTimeImmutable());
        $moderatorOwner->setUpdatedAt(new \DateTimeImmutable());

        $this->save($moderatorOwner);

        return $this->getOrCreate($userId, $groupId);
    }

    public function save(ModeratorOwner $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}
