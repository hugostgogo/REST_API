<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use App\Entity\Customer;
// import request component
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeById(int $id): void
    {
        $entity = $this->find($id);

        if ($entity) {
            $this->getEntityManager()->remove($entity);

            $this->getEntityManager()->flush();
        }
    }

    public function getUsersByPage(Customer $customer, int $page = 1): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.customer = :customer')
            ->setParameter('customer', $customer)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10);

        $doctrinePaginator = new DoctrinePaginator($queryBuilder, false);
        $paginator = new Paginator($doctrinePaginator);

        return $paginator;
    }
}
