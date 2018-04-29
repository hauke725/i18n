<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TranslationValueRepository")
 */
class TranslationValue extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $value;

    /**
     * @var TranslationFile
     * @ORM\ManyToOne(targetEntity="TranslationFile")
     */
    private $file;

    /**
     * TranslationValue constructor.
     * @param string $value
     * @param TranslationFile $file
     */
    public function __construct(string $value, TranslationFile $file)
    {
        $this->file = $file;
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return TranslationFile
     */
    public function getFile(): TranslationFile
    {
        return $this->file;
    }
}
