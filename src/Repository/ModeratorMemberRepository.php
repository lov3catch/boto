<?php

namespace App\Repository;

use App\Entity\ModeratorMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ModeratorMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeratorMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeratorMember[]    findAll()
 * @method ModeratorMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeratorMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeratorMember::class);
    }

    // /**
    //  * @return ModeratorMember[] Returns an array of ModeratorMember objects
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
    public function findOneBySomeField($value): ?ModeratorMember
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getOrCreate(array $options, array $defaults): ModeratorMember
    {
        $entity = $this->findOneBy($options);

        if ($entity instanceof ModeratorMember) return $entity;

        $moderatorMember = new ModeratorMember();
        $moderatorMember->setMemberId($defaults['member_id']);
        $moderatorMember->setMemberFirstName($defaults['member_first_name']);
        $moderatorMember->setMemberUsername($defaults['member_username']);
        $moderatorMember->setMemberIsBot($defaults['member_is_bot']);
        $moderatorMember->setCreatedAt(new \DateTimeImmutable());

//        $moderatorMember->setGroupId($defaults['group_id']);
//        $moderatorMember->setGroupTitle($defaults['group_title']);
//        $moderatorMember->setGroupUsername($defaults['group_username']);
//        $moderatorMember->setGroupType($defaults['group_type']);
//        $moderatorMember->setCreatedAt(new \DateTimeImmutable());
//        $moderatorMember->setUpdatedAt(new \DateTimeImmutable());


        $this->save($moderatorMember);
//        var_dump($defaults);
//        die;

        return $this->getOrCreate($options, $defaults);
    }

    public function save(ModeratorMember $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}
