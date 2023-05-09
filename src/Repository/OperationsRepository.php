<?php

namespace App\Repository;

use App\Entity\Operations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Operations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operations[]    findAll()
 * @method Operations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operations::class);
    }

    // /**
    //  * @return Operations[] Returns an array of Operations objects
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
    public function GroupBy($agency)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.facture = :val')
            ->andWhere('o.agency=:agency')
            ->orWhere('o.agency IS NULL')
            ->setParameter('val', 'OK')
            ->setParameters(['val'=>'Ok', 'agency'=>$agency])
            ->groupBy('o.numero')
            ->orderBy('o.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    

    // public function setFacturation(int $id, $type, float $base, $created_at): array
    // {
    //     $em = $this->getEntityManager()->getConnection();

    //     $req= "SELECT *, u.fullname FROM operations INNER JOIN user u ON u.id=operations.client_id WHERE numero is null AND facture is null AND client_id = ? AND type= ? AND base = ? AND created_at = ?";
    //     $stmt=$em->prepare($req);
    //     $stmt->executeQuery(array($id, $type, $base, $created_at));
       
    //     $data = $stmt->fetchAllAssociative();
    //     return $data;
    // }
    
    public function getOperations($agency)
    {
        $em = $this->getEntityManager()->getConnection();

        $req="  SELECT operations.*, u.fullname, u.type as typee FROM operations INNER JOIN user u ON u.id=operations.client_id WHERE numero is null AND facture is null AND operations.agency_id= ?
                UNION 
                SELECT operations.*, client_id, tempclient FROM operations WHERE client_id is null AND numero is null AND facture is null AND operations.agency_id= ? " 
        ;



        return $em->fetchAllAssociative($req,[$agency,$agency]);

    }
    public function setNumero($type): array
    {
        $em = $this->getEntityManager()->getConnection();
       return $em->fetchAllAssociative("SELECT * FROM operations WHERE type= ? AND numero is not null ORDER BY id DESC LIMIT 1", [$type]);

    }
    public function getLastOps($client): array
    {
        $em = $this->getEntityManager()->getConnection();

        $req= " SELECT * FROM operations WHERE client_id = ? OR tempclient = ? AND facture is null ORDER BY id DESC";
       
        return $em->fetchAllAssociative($req, [$client, $client]);

    }
    public function getBackLastOps($id): array
    {
        $em = $this->getEntityManager()->getConnection();

        $req= " SELECT * FROM operations WHERE id = ? AND facture is null ORDER BY id DESC";

       
        return $em->fetchAllAssociative($req, [$id]);

    }
    
    public function FilterByAgent($agent): array
    {
        $em = $this->getEntityManager()->getConnection();

        $req= " SELECT * FROM operations WHERE agent = ? AND type ='Lot Dubai' AND facture ='Ok' ORDER BY id DESC";
        $em->prepare($req);
        $em->executeQuery(array($agent));
       
        return $em->fetchAllAssociative($req,);
    }

    public function DISTINCT($agency): array
    {
        $em = $this->getEntityManager()->getConnection();

        $req= " SELECT DISTINCT * FROM operations WHERE facture = 'Ok' AND agency_id is ? OR agency_id = ? GROUP BY numero ORDER BY id DESC";
        $em->prepare($req);
        $em->executeQuery([$agency,$agency]);
       
        return $em->fetchAllAssociative();

    }

    
}
