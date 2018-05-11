<?php

namespace App\Repository;

use App\Entity\Action;
use App\Entity\TranslationKey;
use App\Entity\TranslationOccurrence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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

    /**
     * @param TranslationKey $key
     * @return TranslationOccurrence[]
     */
    public function findLatestByKey(TranslationKey $key): array
    {
        $sql = 'SELECT * FROM (
                  SELECT o.*, rank() OVER (
                    PARTITION BY o.action_id ORDER BY o.created, o.id DESC
                  ) FROM translation_occurrence o WHERE o.translation_key_id = :key
                ) as sq WHERE rank = 1';
        return $this->executeLatestQuery($sql, [':key' => $key->getId()]);
    }

    /**
     * @param Action $action
     * @return TranslationOccurrence[]
     */
    public function findLatestByAction(Action $action): array
    {
        $sql = 'SELECT * FROM (
                  SELECT o.*, rank() OVER (
                    PARTITION BY o.translation_key_id ORDER BY o.created, o.id DESC
                  ) FROM translation_occurrence o WHERE o.action_id = :action
                ) as sq WHERE rank = 1';
        return $this->executeLatestQuery($sql, [':action' => $action->getId()]);
    }

    /**
     * @param Action $action
     * @return TranslationOccurrence[]
     */
    public function findLatestByTokenAction(Action $action): array
    {
        $sql = 'SELECT * FROM (
                  SELECT o.*, rank() OVER (
                    PARTITION BY o.translation_key_id ORDER BY o.created, o.id DESC
                  ) FROM translation_occurrence o WHERE o.token_action_id = :action
                ) as sq WHERE rank = 1';
        return $this->executeLatestQuery($sql, [':action' => $action->getId()]);
    }

    private function executeLatestQuery(string $sql, array $params)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(TranslationOccurrence::class, 'o');
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->execute($params);
    }
}
