<?php

namespace App\Repository;

use App\Entity\BucketEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<BucketEntity>
 * @method BucketEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method BucketEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method BucketEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BucketEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BucketEntity::class);
    }

   /**
     * @return BucketEntity[] Returns an array of BucketEntity objects
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findByAuthor(string $author): array
    {
        $entityManager = $this->getEntityManager();
        $dql =
            'SELECT b
            FROM App\Entity\BucketEntity b
            WHERE b.author LIKE :author';
        $query = $entityManager->createQuery($dql);
        $query-> setParameter('author', '%' . $author . '%');
        return $query->getResult();
    }

    public function findByTitle(string $title): array
    {
        $entityManager = $this->getEntityManager();
        $dql =
            'SELECT b
            FROM App\Entity\BucketEntity b
            WHERE b.name LIKE :title';
        $query = $entityManager->createQuery($dql);
        $query-> setParameter('title', '%' . $title . '%');
        return $query->getResult();
    }


    //Il y a une relation One To Many entre CategoryEntity et BucketEntity, je veux filtrer les items par catégorie
    public function findByCategory(int $categoryId): array
    {
        $entityManager = $this->getEntityManager();
        $dql =
            'SELECT b
            FROM App\Entity\BucketEntity b
            JOIN b.category c
            WHERE c.id = :categoryId';
        $query = $entityManager->createQuery($dql);
        $query-> setParameter('categoryId', $categoryId);
        return $query->getResult();
    }


    //On récupère tous les items de la BDD qui sont isPublished = true et triés par date de création


    /**
     * @param int $id
     * @return BucketEntity|null
     */
    public function findById(int $id): ?BucketEntity
    {
        return $this->find($id);
    }

    //Create a new item create($name, $description, $author, ) in the BDD




}
