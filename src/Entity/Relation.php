<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RelationRepository::class)
 */
class Relation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="relations", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $partner;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $status;

    public const PENDING_STATUS = 0;
    public const APPROVED_STATUS = 1;
    public const REJECTED_STATUS = 2;
    public const CANCELED_STATUS = 3;

    /**
     * Relation constructor.
     */
    public function __construct(User $user=null, User $partner=null)
    {
        $this->user = $user;
        $this->partner = $partner;
        $this->status = self::PENDING_STATUS;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPartner(): ?User
    {
        return $this->partner;
    }

    public function setPartner(?User $partner): self
    {
        $this->partner = $partner;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
