<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ShoppingCardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ShoppingCardRepository::class)
 */
class ShoppingCard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sum;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="shoppingCard")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->sum = 0;
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSum(): ?float
    {
        return $this->sum;
    }

    public function setSum(?float $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Order $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setShoppingCard($this);
        }

        return $this;
    }

    public function removeItem(Order $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getShoppingCard() === $this) {
                $item->setShoppingCard(null);
            }
        }

        return $this;
    }

}
