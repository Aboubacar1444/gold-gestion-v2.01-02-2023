<?php

namespace App\Repository;

use App\Entity\Transfert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transfert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transfert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transfert[]    findAll()
 * @method Transfert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransfertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transfert::class);
    }

    // /**
    //  * @return Transfert[] Returns an array of Transfert objects
    //  */
    /* */
    public function findByDate($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.sentAt LIKE :val')
            ->setParameter('val',"%". $value ."%")
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Transfert
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
