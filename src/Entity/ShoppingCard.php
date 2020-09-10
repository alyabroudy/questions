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
     * @ORM\OneToMany(targetEntity=PurchaseProduct::class, mappedBy="shoppingCard")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|PurchaseProduct[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(PurchaseProduct $item): self
    {
                $this->items[] = $item;
                $item->setShoppingCard($this);
        return $this;
    }

    public function removeItem(PurchaseProduct $item): self
    {
        $currentItem = $this->items->get($this->items->indexOf($item));
        if ($this->items->contains($item)) {
            if ($currentItem->getQuantity() > 1){
                $currentItem->setQuantity($currentItem->getQuantity() -1);
            }else{
                $this->items->removeElement($item);
                // set the owning side to null (unless already changed)
                if ($item->getShoppingCard() === $this) {
                    $item->setShoppingCard(null);
                }
            }
        }
        $item->setQuantity($item->getQuantity() + 1);
        return $this;
    }
}
