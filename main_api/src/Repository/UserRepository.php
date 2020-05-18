<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }


    public function findSuperAdmin(): ?User
    {
        return $this->createQueryBuilder('u')
            ->AndWhere('u.roles LIKE :roles ')
            ->setParameter('roles', "%ROLE_SUPER_ADMIN%")
            ->orderBy('u.id','ASC')
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getOneOrNullResult();
        ;

    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->AndWhere('u.email = :email ')
            ->setParameter('email', $email)
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getOneOrNullResult();
        ;

    }

    public function findInvites(int $p_number, int $p_size) : Paginator
    {
        $firstResult = ($p_number-1) * $p_size;
        $maxResult = $firstResult + $p_size;

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT u
            FROM App\Entity\User u
            WHERE u.isActive = :isActive
            ORDER BY u.id DESC'
        )
            ->setParameter('isActive', false)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);
        return $paginator = new Paginator($query, false);
    }


}
