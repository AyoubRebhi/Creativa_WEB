<?php

namespace App\Repository;

use App\Entity\CommandePhp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandePhp>
 *
 * @method CommandePhp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandePhp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandePhp[]    findAll()
 * @method CommandePhp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandePhpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandePhp::class);
    }

//    /**
//     * @return CommandePhp[] Returns an array of CommandePhp objects
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

//    public function findOneBySomeField($value): ?CommandePhp
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
