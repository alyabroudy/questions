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
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="shoppingCard", cascade={"persist", "remove"})
     *
    private $items;
*/
    /**
     * @ORM\OneToMany(targetEntity=ProductOrder::class, mappedBy="shoppingCard", cascade={"persist", "remove"})
     */
    private $orders;

    public function __construct()
    {
       // $this->items = new ArrayCollection();
        $this->sum = 0;
        $this->orders = new ArrayCollection();
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
     *
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Order $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setShoppingCard($this);
            $this->setSum($item->getProduct()->getPrice() + $this->sum);
        }

        return $this;
    }

    public function removeItem(Order $item): self
    {
        dump($this->items);
        if ($this->items->contains($item)) {
            dump("found in card, summe=".$this->sum);
            $this->setSum($this->sum - $item->getProduct()->getPrice() );
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getShoppingCard() === $this) {
                dump("found in card 2, summe=".$this->sum);
                $item->setShoppingCard(null);
            }
            dump($this->items);
        }

        return $this;
    }
*/
    /**
     * @return Collection|ProductOrder[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(ProductOrder $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $this->setSum($this->sum + $order->getProduct()->getPrice() );
            $order->setShoppingCard($this);
        }

        return $this;
    }

    public function removeOrder(ProductOrder $order): self
    {
        $this->setSum($this->sum - $order->getProduct()->getPrice() );
        $this->orders->removeElement($order);
        if ($order->getShoppingCard() === $this) {
            $order->setShoppingCard(null);
        }
        return $this;
    }

    public function removeOrderbyProduct(Product $product): self
    {
dd($this->getOrders());
        foreach ($this->getOrders() as &$o){
            if ($o->getProduct() === $product){
                if ($o->getQuantity() > 1){
                    $o->setQuantity($o->getQuantity() -1);
                }else{
                    $this->orders->removeElement($o);
                    if ($o->getShoppingCard() === $this) {
                        $o->setShoppingCard(null);
                    }
                }
                $this->setSum($this->sum - $o->getProduct()->getPrice() );
                break;
            }
        }
        return $this;
    }



}
