<?php

namespace App\Repository;

use App\Entity\ApiOneTimeAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ApiOneTimeAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiOneTimeAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiOneTimeAccess[]    findAll()
 * @method ApiOneTimeAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiOneTimeAccessRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ApiOneTimeAccess::class);
    }

//    /**
//     * @return ApiOneTimeAccess[] Returns an array of ApiOneTimeAccess objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiOneTimeAccess
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
