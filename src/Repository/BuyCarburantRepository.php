<?php

namespace App\Repository;

use App\Entity\BuyCarburant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BuyCarburant>
 *
 * @method BuyCarburant|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuyCarburant|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuyCarburant[]    findAll()
 * @method BuyCarburant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuyCarburantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuyCarburant::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(BuyCarburant $entity, bool $flush = true): void
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
    public function remove(BuyCarburant $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findBetweenDates($firstDate, $secondDate)
    {
        $query = $this->createQueryBuilder('b');
        $query->andWhere('b.buyAt BETWEEN :val1 and :val2')
            ->setParameter('val1', $firstDate)
            ->setParameter('val2', $secondDate)
            ->orderBy('b.id', 'ASC');


        return $query->getQuery()->getResult() ;
    }
}
