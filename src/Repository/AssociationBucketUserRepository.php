<?php

namespace App\Repository;

use App\Entity\AssociationBucketUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssociationBucketUser>
 *
 * @method AssociationBucketUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssociationBucketUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssociationBucketUser[]    findAll()
 * @method AssociationBucketUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationBucketUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssociationBucketUser::class);
    }

//    /**
//     * @return AssociationBucketUser[] Returns an array of AssociationBucketUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AssociationBucketUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
