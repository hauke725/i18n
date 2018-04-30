<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TranslationValueRepository")
 */
class TranslationValue extends AbstractEntity
{

    /**
     * @var TranslationKey
     * @ORM\ManyToOne(targetEntity="TranslationKey")
     */
    private $translationKey;

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
     * @var Language
     * @ORM\ManyToOne(targetEntity="Language")
     */
    private $language;

    /**
     * TranslationValue constructor.
     * @param TranslationKey $translationKey
     * @param string $value
     * @param Language $language
     * @param TranslationFile $file
     */
    public function __construct(TranslationKey $translationKey, string $value, Language $language, TranslationFile $file)
    {
        $this->translationKey = $translationKey;
        $this->value = $value;
        $this->language = $language;
        $this->file = $file;
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

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return TranslationKey
     */
    public function getTranslationKey(): TranslationKey
    {
        return $this->translationKey;
    }
}
