<?php

namespace App\Repository;

use App\Entity\Project;
use App\Security\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function save(Project $project): void
    {
        $this->_em->persist($project);
        $this->_em->flush();
    }

    public function findProjects(int $firstResult, int $maxResult, ?User $currentUser = null): Paginator
    {
        $query = $this->createQueryBuilder('p');
        if(!is_null($currentUser)){
            $query
                ->innerJoin("App\Entity\ProjectUser", 'pu')
                ->andWhere("p.id = pu.project")
                ->andWhere("pu.user = :user_id")
                ->setParameter('user_id', $currentUser->getId());
        }
        $query
            ->orderBy('p.id', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult)
            ->getQuery();
        return $paginator = new Paginator($query, false);
    }

    public function findProjectForUser(int $id, ?User $currentUser): ?Project
    {
        return $this
            ->createQueryBuilder('p')
            ->innerJoin("App\Entity\ProjectUser", 'pu')
            ->andWhere("p.id = :id")
            ->andWhere("p.id = pu.project")
            ->andWhere("pu.user = :user_id")
            ->setParameter('id', $id)
            ->setParameter('user_id', $currentUser->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }

}