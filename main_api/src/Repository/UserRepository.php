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

    public function save(User $user): void
    {
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

    //using in provider
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
    private function filterOutputWithPagination(int $pNumber, int $pSize, bool $isActive, bool $isLocked = false ):Paginator
    {
        $firstResult = ($pNumber-1) * $pSize;
        $maxResult = $firstResult + $pSize;

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT u
            FROM App\Entity\User u
            WHERE u.isActive = :isActive AND u.isLocked = :isLocked
            ORDER BY u.id DESC'
        )
            ->setParameter('isActive', $isActive)
            ->setParameter('isLocked', $isLocked)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);
        return $paginator = new Paginator($query, false);
    }
    public function findInvites(int $pNumber, int $pSize) : Paginator
    {
        return self::filterOutputWithPagination($pNumber, $pSize, false);
    }

    public function findActiveUsers(int $pNumber, int $pSize) : Paginator
    {
        return self::filterOutputWithPagination($pNumber, $pSize, true, false);
    }

    public function findLockedUsers(int $pNumber, int $pSize) : Paginator
    {
        return self::filterOutputWithPagination($pNumber, $pSize, true, true);
    }

    public function findInvite(int $id) : ?User
    {
        return $this->createQueryBuilder('u')
            ->AndWhere('u.id = :id ')
            ->AndWhere('u.isActive = :isActive ')
            ->setParameter('id', $id)
            ->setParameter('isActive', false)
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getOneOrNullResult();
        ;
    }

    public function findInviteAdmin() : ?User
    {
        return $this->createQueryBuilder('u')
            ->AndWhere('u.roles LIKE :roles ')
            ->AndWhere('u.isActive = :isActive ')
            ->setParameter('roles', "%ROLE_SUPER_ADMIN%")
            ->setParameter('isActive', false)
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getOneOrNullResult();
        ;
    }

    public function findActiveUser(int $id) : ?User
    {
        return $this->createQueryBuilder('u')
            ->AndWhere('u.id = :id ')
            ->AndWhere('u.isActive = :isActive ')
            ->AndWhere('u.isLocked = :isLocked ')
            ->setParameter('id', $id)
            ->setParameter('isActive', true)
            ->setParameter('isLocked', false)
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getOneOrNullResult();
        ;
    }

}
