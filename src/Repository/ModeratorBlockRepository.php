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
    public const BAN_STRATEGY_LOCAL = '/block';
    public const BAN_STRATEGY_GLOBAL = '/block-all';
    public const BAN_STRATEGY_TOTAL = '/block-all-total';


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

    public function doBlocLocal(int $userId, int $adminId, int $groupId)
    {
        return $this->doBlock($userId, $adminId, $groupId, self::BAN_STRATEGY_LOCAL);
    }


    public function doBlockGlobal(int $userId, int $adminId, int $groupId)
    {
        return $this->doBlock($userId, $adminId, $groupId, self::BAN_STRATEGY_GLOBAL);
    }

    public function doBlockTotal(int $userId, int $adminId, int $groupId)
    {
        return $this->doBlock($userId, $adminId, $groupId, self::BAN_STRATEGY_TOTAL);
    }

    private function doBlock(int $userId, int $adminId, int $groupId, string $strategy)
    {
        $block = new ModeratorBlock();
        $block->setUserId($userId);
        $block->setAdminId($adminId);
        $block->setGroupId($groupId);
        $block->setStrategy($strategy);
        $block->setCreatedAt(new \DateTimeImmutable());
        $block->setUpdatedAt(new \DateTimeImmutable());

        $this->save($block);
    }

    public function save(ModeratorBlock $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}
