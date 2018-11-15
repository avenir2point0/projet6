<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findCommentById($id, $page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u')
            ->addSelect('u')
            ->andWhere('c.trick = :id')
            ->setParameter('id', $id)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
        ;

        $query->setFirstResult(($page-1) * $nbPerPage)
            ->setMaxResults($nbPerPage)
        ;

        return new Paginator($query, $fetchJoinCollection = true);
    }

}
