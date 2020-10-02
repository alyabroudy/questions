<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Product::class, cascade={"persist", "remove"})
     */
    private $product;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $orderDate;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=ShoppingCard::class, inversedBy="items")
     */
    private $shoppingCard;

    /**
     * Order constructor.
     * @param $product
     *
     */
    public function __construct($product,  $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(?\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getShoppingCard(): ?ShoppingCard
    {
        return $this->shoppingCard;
    }

    public function setShoppingCard(?ShoppingCard $shoppingCard): self
    {
        $this->shoppingCard = $shoppingCard;

        return $this;
    }


}
