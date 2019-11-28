<?php

namespace App\Repository;

use App\Entity\TypeTricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeTricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeTricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeTricks[]    findAll()
 * @method TypeTricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeTricksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeTricks::class);
    }

    public function getTricksType()
    {
        return $this->createQueryBuilder('u')
            ->select('u.name_tricks')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return TypeTricks[] Returns an array of TypeTricks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeTricks
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
