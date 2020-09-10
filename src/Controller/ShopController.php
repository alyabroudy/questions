<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\PurchaseProduct;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop")
 */
class ShopController extends AbstractController
{
    /**
     * @Route("/", name="shop_page")
     */
    public function index()
    {
        $purchaseProducts = $this->getDoctrine()->getManager()->getRepository(PurchaseProduct::class)->findAll();

        return $this->render('shop/index.html.twig', [
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
     * @Route("/additemtocard/{id}", name="add_item_to_card", methods={"POST"})
     */
    public function addItemToCard(PurchaseProduct $pProduct): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $card= $user->getShoppingCard();

        if (!$card->getItems()->contains($pProduct)) {
        $card->addItem($pProduct);
        }else{
           $currentProduct= $card->getItems()->get($card->getItems()->indexOf($pProduct));
           $currentProduct->setQuantity($currentProduct->getQuantity() +1);
        }
        $pProduct->setQuantity($pProduct->getQuantity() -1);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('shop_page');
    }


}
