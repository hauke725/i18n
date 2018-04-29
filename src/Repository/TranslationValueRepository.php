<?php

namespace App\Repository;

use App\Entity\TranslationValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TranslationValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationValue[]    findAll()
 * @method TranslationValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationValueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TranslationValue::class);
    }

//    /**
//     * @return TranslationValue[] Returns an array of TranslationValue objects
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
    public function findOneBySomeField($value): ?TranslationValue
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
