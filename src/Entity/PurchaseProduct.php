<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PurchaseProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PurchaseProductRepository::class)
 */
class PurchaseProduct
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
     * @ORM\ManyToOne(targetEntity=ShoppingCard::class, inversedBy="items")
     */
    private $shoppingCard;

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
