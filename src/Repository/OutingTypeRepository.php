<?php

namespace App\Repository;

use App\Entity\OutingType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OutingType|null find($id, $lockMode = null, $lockVersion = null)
 * @method OutingType|null findOneBy(array $criteria, array $orderBy = null)
 * @method OutingType[]    findAll()
 * @method OutingType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutingType::class);
    }

    // /**
    //  * @return OutingType[] Returns an array of OutingType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OutingType
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
