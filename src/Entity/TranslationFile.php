<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TranslationFileRepository")
 */
class TranslationFile extends AbstractEntity
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * TranslationFile constructor.
     * @param $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValues(Language $language)
    {

    }
}
