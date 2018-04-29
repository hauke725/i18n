<?php

namespace App\Repository;

use App\Entity\TranslationOccurrence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TranslationOccurrence|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationOccurrence|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationOccurrence[]    findAll()
 * @method TranslationOccurrence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationOccurrenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TranslationOccurrence::class);
    }

//    /**
//     * @return TranslationOccurrence[] Returns an array of TranslationOccurrence objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TranslationOccurrence
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
