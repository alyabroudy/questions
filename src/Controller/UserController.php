<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
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
}
