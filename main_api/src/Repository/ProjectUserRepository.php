<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Security\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @method ProjectUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectUser[]    findAll()
 * @method ProjectUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectUser::class);
    }

   public function findProjectMembers(Project $project):?array
   {
       return $this->_em
           ->createQuery("SELECT pu, u FROM App\Entity\ProjectUser pu INNER JOIN pu.user u WHERE pu.project = :project ")
           ->setParameter('project', $project)
           ->execute();
   }

}
