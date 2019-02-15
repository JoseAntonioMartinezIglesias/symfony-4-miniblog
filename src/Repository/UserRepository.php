<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class UserRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, User::class);
    }

    /**
     * @param type $page
     * @return \App\Repository\Pagerfanta
     */
    public function findLatest($page) : Pagerfanta {       
        $qb = $this->createQueryBuilder('p')
                ->orderBy('p.id', 'DESC');
        return $this->createPaginator($qb->getQuery(), $page);       
    }

    /**
     * @param \App\Repository\Query $query
     * @param int $page
     * @return \App\Repository\Pagerfanta
     */
    private function createPaginator(Query $query, int $page): Pagerfanta {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(User::NUM_ITEMS);
        $paginator->setCurrentPage($page);
        return $paginator;
    }

}
