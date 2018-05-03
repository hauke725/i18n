<?php

namespace App\Repository;

use App\Entity\Language;
use App\Entity\TranslationFile;
use App\Entity\TranslationValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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

    /**
     * @param TranslationFile $file
     * @param Language $language
     * @return TranslationValue[]
     */
    public function findLatestByFileAndLanguage(TranslationFile $file, Language $language): array
    {
        $sql = 'SELECT * FROM (
                  SELECT tv.*, rank() OVER (
                    PARTITION BY tv.translation_key_id ORDER BY tv.created DESC
                  ) FROM translation_value tv, translation_key tk WHERE tv.file_id = :file AND tv.language_id = :lang AND tv.translation_key_id = tk.id AND tk.deleted = false
                ) as sq WHERE rank = 1';
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(TranslationValue::class, 'tv');
        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->execute([
            ':lang' => $language->getId(), ':file' => $file->getId(),
        ]);
    }
}
