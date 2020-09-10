<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/homepage", name="home_page")
     */
    public function index()
    {
        //<li>Quiz App Go to: <code><a href="{{ '/var/www/src/Controller/HomePageController.php'|file_link(0) }}">src/Controller/HomePageController.php</a></code></li>
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }
}
