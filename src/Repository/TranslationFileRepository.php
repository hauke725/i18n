<?php

namespace App\Repository;

use App\Entity\TranslationFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TranslationFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationFile[]    findAll()
 * @method TranslationFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TranslationFile::class);
    }

//    /**
//     * @return TranslationFile[] Returns an array of TranslationFile objects
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
    public function findOneBySomeField($value): ?TranslationFile
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
