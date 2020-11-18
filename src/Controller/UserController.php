<?php

namespace App\Controller;

use App\Entity\Relation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
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
     * @Route("/", name="users_page")
     */
    public function index()
    {
        return $this->render('user/shop_homepage.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    public function getUsers()
    {
        return $this->render('user/shop_homepage.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/addFriend/{id}", name="add_friend", methods={"POST"})
     * @param User $friend
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addFriend(User $partner)
    {
        /** @var User $user */
        $user = $this->getUser();

        // dd($partner->getEmail(), $user->getEmail(), $user->getId(), $partner->getId(), $relation);
        if (!$user->isFriend($partner)){
            $relation = new Relation($user, $partner);
            //dump($relation);
            $this->getDoctrine()->getManager()->persist($relation);
            $this->getDoctrine()->getManager()->flush();
            //dump('relation should be ok');
            return new JsonResponse(['message'=>'Friend Added!!']);
        }
        //dd('controller');
        return new JsonResponse(['message'=>'Error']);
    }

    /**
     * @Route("/removeFriend/{id}", name="remove_friend", methods={"POST"})
     * @param User $friend
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeFriend(User $partner)
    {
        /** @var User $user */
        $user = $this->getUser();
        // dd($partner->getEmail(), $user->getEmail(), $user->getId(), $partner->getId(), $relation);

        $em = $this->entityManager->getRepository(Relation::class);

        $relation= $em->findRelationByUserAndPartner($user, $partner);

        if (null !== $relation){
            $relation->setStatus(Relation::CANCELED_STATUS);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(['message'=>'Request Canceled!!']);
        }

        //$this->getDoctrine()->getManager()->flush();
        //dump('relation should be ok');
        return new JsonResponse(['message'=>'Error!!']);
    }

    /**
     * @Route("/approveFriend/{id}", name="approve_friend", methods={"POST"})
     * @param User $friend
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approveFriend(User $partner)
    {
        /** @var User $user */
        $user = $this->getUser();
        // dd($partner->getEmail(), $user->getEmail(), $user->getId(), $partner->getId(), $relation);

        $em = $this->entityManager->getRepository(Relation::class);

        $relation= $em->findRelationRequestByUserAndPartner($user, $partner);

        if (null !== $relation){
            $relation->setStatus(Relation::APPROVED_STATUS);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(['message'=>'Friend Approved!!']);
        }

        //$this->getDoctrine()->getManager()->flush();
        //dump('relation should be ok');
        return new JsonResponse(['message'=>'Error!!']);
    }

    /**
     * @Route("/rejectFriend/{id}", name="reject_friend", methods={"POST"})
     * @param User $friend
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rejectFriend(User $partner)
    {
        /** @var User $user */
        $user = $this->getUser();
        // dd($partner->getEmail(), $user->getEmail(), $user->getId(), $partner->getId(), $relation);

        $em = $this->entityManager->getRepository(Relation::class);

        $relation= $em->findRelationRequestByUserAndPartner($user, $partner);

        if (null !== $relation){
            $relation->setStatus(Relation::REJECTED_STATUS);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(['message'=>'Friend rejected!!']);
        }

        //$this->getDoctrine()->getManager()->flush();
        //dump('relation should be ok');
        return new JsonResponse(['message'=>'Error!!']);
    }
}
