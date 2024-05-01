<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id_User, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $passwordEncoder;

    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($registry, User::class);
        // $this->passwordEncoder = $passwordEncoder;
    }

    public function findUserByEmailAndPassword(string $email, string $password)
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            return false; // Utilisateur non trouvé
        }

        // Vérifiez si le mot de passe correspond
        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            return false; // Mot de passe incorrect
        }

        return $user;
    }




    public function findByCriteria($criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        if (!empty($criteria['lastName'])) {
            $queryBuilder->andWhere('u.last_name LIKE :lastName')
                ->setParameter('lastName', '%' . $criteria['lastName'] . '%');
        }

        if (!empty($criteria['firstName'])) {
            $queryBuilder->andWhere('u.first_name LIKE :firstName')
                ->setParameter('firstName', '%' . $criteria['firstName'] . '%');
        }

        if (!empty($criteria['role'])) {
            $queryBuilder->andWhere('u.role = :role')
                ->setParameter('role', $criteria['role']);
        }

        if (!empty($criteria['numtel'])) {
            $queryBuilder->andWhere('u.numTel = :numtel')
                ->setParameter('numtel', $criteria['numtel']);
        }

        // Ajoutez d'autres critères de recherche si nécessaire

        return $queryBuilder->getQuery()->getResult();
    }


    public function findBySearchQuery($searchQuery)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.lastName LIKE :query OR u.firstName LIKE :query OR u.role LIKE :query OR u.numtel LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();
    }
    // //new
    // public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    // {
    //     if (!$user instanceof User) {
    //         throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
    //     }

    //     $user->setPassword($newHashedPassword);

    //     $this->add($user, true);
    // }



    public function countUsersByRole(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.role, COUNT(u.idUser) AS userCount')
            ->groupBy('u.role')
            ->getQuery()
            ->getResult();
    }
}
