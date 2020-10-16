<?php

namespace App\Repository;

use App\Entity\TakenBooks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TakenBooks|null find($id, $lockMode = null, $lockVersion = null)
 * @method TakenBooks|null findOneBy(array $criteria, array $orderBy = null)
 * @method TakenBooks[]    findAll()
 * @method TakenBooks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TakenBooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TakenBooks::class);
    }



    // /**
    //  * @return TakenBooks[] Returns an array of TakenBooks objects
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
    public function findOneBySomeField($value): ?TakenBooks
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
