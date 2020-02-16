<?php

namespace App\Repository;

use App\Entity\ModeratorStart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ModeratorStart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorStart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorStart[]    findAll()
 * @method ModeratorStart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorStartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeratorStart::class);
    }

    // /**
    //  * @return ModeratorStart[] Returns an array of ModeratorStart objects
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
    public function findOneBySomeField($value): ?ModeratorStart
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getOrCreate(int $botId, int $userId): ModeratorStart
    {
        $entity = $this->findOneBy(['bot_id' => $botId, 'user_id' => $userId]);

        if ($entity instanceof ModeratorStart) return $entity;

        $moderatorStart = new ModeratorStart();
        $moderatorStart->setBotId($botId);
        $moderatorStart->setUserId($userId);
        $moderatorStart->setIsSuperuser(false);
        $moderatorStart->setCreatedAt(new \DateTimeImmutable());
        $moderatorStart->setUpdatedAt(new \DateTimeImmutable());

        $this->save($moderatorStart);

        return $this->getOrCreate($botId, $userId);
    }

    public function save(ModeratorStart $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}
