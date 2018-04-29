<?php
/**
 * Created by PhpStorm.
 * User: hauke
 * Date: 28/04/18
 * Time: 17:09
 */

namespace App\Entity;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractEntity
 * @package App
 * @ORM\MappedSuperclass()
 * @ORM\Table(indexes={@ORM\Index(columns={"created"})})
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractEntity
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @var DateTimeImmutable|null
     */
    private $created;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreated(): ?DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @internal this is automatically called on persist and should not be called manually
     * @throws \BadMethodCallException
     * @ORM\PrePersist()
     */
    public function setCreated()
    {
        if ($this->created) {
            throw new \BadMethodCallException('already set');
        }
        $this->created = new DateTimeImmutable();
    }
}