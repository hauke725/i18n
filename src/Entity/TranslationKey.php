<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TranslationKeyRepository")
 */
class TranslationKey extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var TranslationOccurrence[]
     * @ORM\OneToMany(targetEntity="TranslationOccurrence", mappedBy="translationKey")
     */
    private $occurrences;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $deleted = false;

    /**
     * TranslationKey constructor.
     * @param $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->occurrences = new ArrayCollection();
    }


    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TranslationOccurrence[]|Collection
     */
    public function getOccurrences(): Collection
    {
        return $this->occurrences;
    }

    public function getFirstOccurrence(): ?TranslationOccurrence
    {
        return $this->getOccurrences()->isEmpty() ? null : $this->getOccurrences()->first();
    }

    public function getLastOccurrence(): ?TranslationOccurrence
    {
        return $this->getOccurrences()->isEmpty() ? null : $this->getOccurrences()->last();
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }
}
