<?php

namespace App\Repository;

use App\Entity\CodePromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Codepromo>
 *
 * @method Codepromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Codepromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Codepromo[]    findAll()
 * @method Codepromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodepromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry,Codepromo::class);
    }

//    /**
//     * @return Codepromo[] Returns an array of Codepromo objects
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

//    public function findOneBySomeField($value): ?Codepromo
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }




}
