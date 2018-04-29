<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LanguageRepository")
 */
class Language extends AbstractEntity
{

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $name;

    /**
     * Language constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }


    public function getName(): ?string
    {
        return $this->name;
    }
}
