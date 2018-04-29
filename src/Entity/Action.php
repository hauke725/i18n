<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
 */
class Action extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var TranslationOccurrence[]
     * @ORM\OneToMany(targetEntity="TranslationOccurrence", mappedBy="action")
     */
    private $occurrences;

    /**
     * Action constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->occurrences = new ArrayCollection();
        $this->name = $name;
    }

    public function getName(): ?string
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
}
