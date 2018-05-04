<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiOneTimeAccessRepository")
 */
class ApiOneTimeAccess extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @var ApiUser
     * @ORM\ManyToOne(targetEntity="App\Entity\ApiUser")
     */
    private $apiUser;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return ApiUser
     */
    public function getApiUser(): ApiUser
    {
        return $this->apiUser;
    }

    /**
     * @param ApiUser $apiUser
     */
    public function setApiUser(ApiUser $apiUser): void
    {
        $this->apiUser = $apiUser;
    }
}
