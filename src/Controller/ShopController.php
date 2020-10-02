<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PurchaseProduct;
use App\Entity\ShoppingCard;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop")
 */
class ShopController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="shop_page")
     */
    public function shopHomePage()
    {
        $purchaseProducts = $this->getDoctrine()->getManager()->getRepository(PurchaseProduct::class)->findAll();

        return $this->render('shop/shop_homepage.html.twig', [
            'purchaseProducts' => $purchaseProducts,
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('/shop/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/shoppingCard/{id}", name="shopping_card_show", methods={"GET"})
     */
    public function shoppingCardShow(ShoppingCard $card): Response
    {
        return $this->render('shop/shop_shopping_card_show.html.twig', [
            'card' => $card,
        ]);
    }

    /**
     * @Route("/additemtocard/{id}", name="add_item_to_card", methods={"POST"})
     */
    public function addItemToCard(PurchaseProduct $pProduct): Response
    {

        //refresh product
        //$this->getDoctrine()->getManager()->refresh($pProduct->getProduct());
        /** @var User $user */
        $user = $this->getUser();
        /** @var ShoppingCard $card */
        $card= $user->getShoppingCard();
        $itemsOnUserCard= $card->getItems();

           $existingOrder=null;
           dump(count($itemsOnUserCard));
           if (count($itemsOnUserCard) > 0){
               foreach ($itemsOnUserCard as $o){
                   if ($o->getProduct()->getId() === $pProduct->getProduct()->getId()){
                       $existingOrder = $o;
                       break;
                   }
               }
           }
           //dump($itemsOnUserCard, $card, null == $card, null == $itemsOnUserCard);
        if (null === $existingOrder){
            $existingOrder = new Order($pProduct->getProduct(), 1);
            $card->addItem($existingOrder);
            $this->entityManager->persist($existingOrder);
        }else{
            $existingOrder->setQuantity($existingOrder->getQuantity() +1);
        }
        $card->setSum($card->getSum() + $existingOrder->getProduct()->getPrice());
        // dump($existingOrder, $itemsOnUserCard ,$pProduct->getProduct());
        $pProduct->setQuantity($pProduct->getQuantity() -1);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('shop_page');
    }


}
