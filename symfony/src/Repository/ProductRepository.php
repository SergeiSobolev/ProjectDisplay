<?php

namespace App\Repository;

use App\Entity\Product;
use App\Form\Objects\SearchObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findProductsPaginated($limit, $page): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setFirstResult($page)
            ->setMaxResults($limit)
        ;


        return new Paginator($qb);
    }


    public function searchByQuery(SearchObject $object)
    {

        $qb = $this->createQueryBuilder('p');

        $name = $object->getName();

        if ($name !== null){
            $qb
                ->where('p.name LIKE :name')
                ->setParameter('name', '%'. $name. '%');
        }

        $maxPrice = $object->getMaxPrice();

        if ($maxPrice !== null){
            $qb
                ->andWhere('p.price < :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        $minPrice = $object->getMinPrice();

        if ($minPrice !== null){
            $qb
                ->andWhere('p.price > :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        $category = $object->getCategory();

        if ($category !== null){
            $qb
                ->andWhere('p.category IN (:category)')
                ->setParameter('category', $category);
        }

        return $qb->getQuery()->getResult();
    }

    public function getCountProductFromCategory($categoryId): int
    {
        return $this->createQueryBuilder('p')
            ->where('p.category = :categoryId')
            ->select('count(p)')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getSingleScalarResult();

    }

    public function searchByApiId(int $idApi): ?Product
    {
        return $this->findOneBy(['idApi' => $idApi]);
    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
