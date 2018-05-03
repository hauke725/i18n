<?php

namespace App\Repository;

use App\Entity\Action;
use App\Entity\TranslationOccurrence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Action|null find($id, $lockMode = null, $lockVersion = null)
 * @method Action|null findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Action::class);
    }

    /**
     * finds the first action that is one instance of a group of duplicates (concerning action names)
     * @return Action[]
     */
    public function findInstanceOfDuplicates(): array
    {
        $sql = 'SELECT * FROM (
                  SELECT a.*, (select count(id) from action where name = a.name) as num, rank() OVER (
                    PARTITION BY a.name ORDER BY a.id ASC
                  ) FROM action a
                ) as sq WHERE rank = 1 AND num > 1';
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Action::class, 'a');
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->execute();
    }
}
