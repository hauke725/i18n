<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TranslationOccurrenceRepository")
 */
class TranslationOccurrence extends AbstractEntity
{

    /**
     * @var Action
     * @ORM\ManyToOne(targetEntity="Action")
     */
    private $action;

    /**
     * @var TranslationKey
     * @ORM\ManyToOne(targetEntity="TranslationKey", inversedBy="occurrences")
     */
    private $translationKey;

    /**
     * TranslationOccurrence constructor.
     * @param Action $action
     * @param TranslationKey $translationKey
     */
    public function __construct(Action $action, TranslationKey $translationKey)
    {
        $translationKey->getOccurrences()->add($this);
        $action->getOccurrences()->add($this);
        $this->translationKey = $translationKey;
        $this->action = $action;
    }


    public function getAction(): Action
    {
        return $this->action;
    }

    /**
     * @return TranslationKey
     */
    public function getTranslationKey(): TranslationKey
    {
        return $this->translationKey;
    }
}
