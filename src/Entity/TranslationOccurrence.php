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
     * @ORM\ManyToOne(targetEntity="Action", inversedBy="occurrences")
     */
    private $action;
    /**
     * @var Action
     * @ORM\ManyToOne(targetEntity="Action", inversedBy="tokenOccurrences")
     */
    private $tokenAction;

    /**
     * @var TranslationKey
     * @ORM\ManyToOne(targetEntity="TranslationKey", inversedBy="occurrences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $translationKey;

    /**
     * TranslationOccurrence constructor.
     * @param Action $action
     * @param TranslationKey $translationKey
     * @param Action|null $tokenAction
     */
    public function __construct(Action $action, TranslationKey $translationKey, Action $tokenAction = null)
    {
        $this->translationKey = $translationKey;
        $translationKey->getOccurrences()->add($this);
        $action->getOccurrences()->add($this);
        $this->action = $action;
        if ($tokenAction) {
            $tokenAction->getTokenOccurrences()->add($this);
            $this->tokenAction = $tokenAction;
        }
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
