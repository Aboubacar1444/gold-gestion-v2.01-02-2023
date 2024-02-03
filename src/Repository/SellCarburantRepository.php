<?php

namespace App\Repository;

use App\Entity\SellCarburant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SellCarburant>
 *
 * @method SellCarburant|null find($id, $lockMode = null, $lockVersion = null)
 * @method SellCarburant|null findOneBy(array $criteria, array $orderBy = null)
 * @method SellCarburant[]    findAll()
 * @method SellCarburant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SellCarburantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SellCarburant::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(SellCarburant $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(SellCarburant $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findBetweenDates($firstDate, $secondDate)
    {
          $query = $this->createQueryBuilder('s');
           $query->andWhere('s.buyAt BETWEEN :val1 and :val2')
            ->setParameter('val1', $firstDate)
            ->setParameter('val2', $secondDate)
            ->orderBy('s.id', 'ASC');


        return $query->getQuery()->getResult() ;
    }


    /*
    public function findOneBySomeField($value): ?SellCarburant
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
