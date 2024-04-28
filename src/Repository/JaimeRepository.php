<?php

namespace App\Repository;

use App\Entity\Jaime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jaime>
 *
 * @method Jaime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jaime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jaime[]    findAll()
 * @method Jaime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JaimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jaime::class);
    }

    //    /**
    //     * @return Jaime[] Returns an array of Jaime objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Jaime
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
