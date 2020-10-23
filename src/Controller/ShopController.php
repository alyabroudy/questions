<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductOrder;
use App\Entity\PurchaseProduct;
use App\Entity\ShoppingCard;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        /*
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->normalize($purchaseProducts, 'json');

        $arrayP =  $serializer->normalize($purchaseProducts,
            null, [
                AbstractNormalizer::ATTRIBUTES=>[
                    'id', 'quantity','product' =>['id', 'name', 'description', 'price', 'image', 'Category', 'createdAt'],
                ]
            ]
        ) ;
        $jsonP = $serializer->encode($arrayP, 'json');

        return new JsonResponse($jsonP);
        */
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     * Shows a Product
     */
    public function show(Product $product): Response
    {
        return $this->render('/shop/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/shoppingCard/{id}", name="shopping_card_show", methods={"GET"})
     * Shows a ShoppingCard items
     */
    public function shoppingCardShow(ShoppingCard $card): Response
    {
        return $this->render('shop/shop_shopping_card_show.html.twig', [
            'card' => $card,
        ]);
    }

    /**
     * @Route("/additemtocard/{id}", name="add_item_to_card", methods={"POST"})
     * Adds Order to User ShoppingCard
     * @param PurchaseProduct $pProduct
     * @return Response
     */
    public function addItemToCard(PurchaseProduct $pProduct): Response
    {
        //$this->getDoctrine()->getManager()->refresh($pProduct->getProduct());

        /** @var User $user */
        $user = $this->getUser();

        /** @var ShoppingCard $card */
        $card= $user->getShoppingCard();

        $existingOrder= $this->isProductExistInCard($pProduct->getProduct());
       //dd($existingOrder);
        if (null === $existingOrder){
            $existingOrder = new ProductOrder($pProduct->getProduct(), 1);
            $card->addOrder($existingOrder);
            $this->entityManager->persist($existingOrder);
        }else{
            $existingOrder->setQuantity($existingOrder->getQuantity() +1);
        }
        // dump($existingOrder, $itemsOnUserCard ,$pProduct->getProduct());
        $pProduct->setQuantity($pProduct->getQuantity() -1);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('shop_page');
    }

    /**
     * @Route("/ss/{id}", name="ss", methods={"POST"})
     */
    public function addItemToCard2(PurchaseProduct $pProduct, Request $request): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        /** @var ShoppingCard $card */
        $card= $user->getShoppingCard();

        $existingOrder= $this->isProductExistInCard($pProduct->getProduct());
        //dd($existingOrder);

        if (null === $existingOrder){
            $existingOrder = new ProductOrder($pProduct->getProduct(), 1);
            $card->addOrder($existingOrder);
            $this->entityManager->persist($existingOrder);
        }else{
            $existingOrder->setQuantity($existingOrder->getQuantity() +1);
        }
        // dump($existingOrder, $itemsOnUserCard ,$pProduct->getProduct());
        $pProduct->setQuantity($pProduct->getQuantity() -1);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'id'=>$pProduct->getId(),
            'cardCount'=> count($card->getOrders()),
            "remainingQuantity"=> $pProduct->getQuantity()
        ]);
    }

    /**
     * @Route("/removeItemFromCard/{id}", name="remove_item_from_card", methods={"POST"})
     * Removes ProductOrder from User ShoppingCard
     * @param PurchaseProduct $pProduct
     * @return Response
     */
    public function removeItemFromCard(ProductOrder $productOrder): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var ShoppingCard $card */
        $card= $user->getShoppingCard();

        $existingOrder = $this->isOrderExist($productOrder);

        $deleted= false;
        if (null !== $existingOrder){
            if ($existingOrder->getQuantity() > 1){
                $existingOrder->setQuantity($existingOrder->getQuantity() -1);
            }else{
                $card->removeOrder($existingOrder);
                $this->getDoctrine()->getManager()->remove($existingOrder);
            }
            $deleted = true;
        }
        if ($deleted){
            $purchaseProduct= $this->getPurchaseProductByProduct($productOrder->getProduct());
            $purchaseProduct->setQuantity($purchaseProduct->getQuantity() +1);
            $this->addFlash('success', "Product #". $purchaseProduct->getProduct()->getId() . " Removed From Shopping Card!");
        }

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('shopping_card_show', ['id'=> $card->getId()]);
    }

    private function isProductExistInCard($product): ?ProductOrder
    {
        $qb = $this->getDoctrine()->getRepository(ProductOrder::class)->createQueryBuilder('o');
        $result=$qb->where('o.product = :id')
            ->setParameter('id', $product->getId())
            ->getQuery()->getOneOrNullResult();
        return $result;
    }

    private function getPurchaseProductByProduct($product): ?PurchaseProduct
    {
        $qb = $this->getDoctrine()->getRepository(PurchaseProduct::class)->createQueryBuilder('pp');
        $result=$qb->where('pp.product = :id')
            ->setParameter('id', $product->getId())
            ->getQuery()->getOneOrNullResult();
        return $result;
    }


    private function isOrderExist(ProductOrder $productOrder): ?ProductOrder
    {
        $qb = $this->getDoctrine()->getRepository(ProductOrder::class)->createQueryBuilder('o');
        $result=$qb->where('o.id = :id')
            ->setParameter('id', $productOrder->getId())
            ->getQuery()->getOneOrNullResult();
        return $result;
    }

}
