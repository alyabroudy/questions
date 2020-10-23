<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\Relation;
use App\Entity\User;
use App\Repository\RelationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/linker")
 */
class LinkerController extends AbstractController
{
    private $client;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->client = $client;
    }

    /**
     * @Route("/", name="linker_page")
     */
    public function index()
    {
        return $this->render('linker/linker_homepage.html.twig', [
            'controller_name' => 'LinkerController',
        ]);
    }

    /**
     * @Route("/user", name="user_page")
     */
    public function user()
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('linker/linker_userpage.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/friends", name="friends_page")
     */
    public function friends()
    {
        $user = $this->getUser();
        $approvedRelations = $this->getDoctrine()->getRepository(Relation::class)
            ->findRelationsOfUserByStatus($user, Relation::APPROVED_STATUS);

        $pendingRelations = $this->getDoctrine()->getRepository(Relation::class)
            ->findRelationsOfUserByStatus($user, Relation::PENDING_STATUS);

        return $this->render('linker/linker_friendspage.html.twig', [
            'relations' => $approvedRelations,
            'pendingRelations' => $pendingRelations,
        ]);
    }

    /**
     * @Route("/favorites", name="favorites_page")
     */
    public function favorites()
    {
        /** @var User $user */
        $user = $this->getUser();

        $links = $this->getDoctrine()->getRepository(Link::class)
            ->findUserFavoriteLinks($user);

        return $this->render('linker/linker_favoritespage.html.twig', [
            'links' => $links,
        ]);
    }

    /**
     * get public Links of user friends
     * @Route("/public", name="public_page")
     */
    public function publicLinks()
    {
        /** @var User $user */
        $user = $this->getUser();
        //$publicLinks = new ArrayCollection($this->getDoctrine()->getRepository(Link::class)->findUserPublicLinks($user));
        $relations = $this->getDoctrine()->getRepository(Relation::class)
            ->findRelationsOfUserByStatus($user, Relation::APPROVED_STATUS);
        return $this->render('linker/linker_public_page.html.twig', [
            'relations' => $relations,
        ]);
    }

    /**
     * get user Links
     * @Route("/private", name="private_page")
     */
    public function privateLinks()
    {
        /** @var User $user */
        $user = $this->getUser();
/*
        $link = new Link();
        $link->setUrl(
            'https://akwam.co/episode/15143/ratched-%D8%A7%D9%84%D9%85%D9%88%D8%B3%D9%85-%D8%A7%D9%84%D8%A7%D9%88%D9%84/%D8%A7%D9%84%D8%AD%D9%84%D9%82%D8%A9-6'
        );
        $link->setHostName(Link::HOST_AKWAM);
        $vLink=$this->generateVideoLink($link);
*/
        //$publicLinks = new ArrayCollection($this->getDoctrine()->getRepository(Link::class)->findUserPublicLinks($user));
        $links = $this->getDoctrine()->getRepository(Link::class)
            ->findUserLinks($user);
        foreach ($links as $l){
            $l->setUrl($this->generateVideoLink($l));
        }

        return $this->render('linker/linker_private_page.html.twig', [
            'links' => $links,
        ]);

    }

    /**
     * get user Links
     * @Route("/addlink", name="add_link_page")
     */
    public function createLink()
    {
        /** @var User $user */
        $user = $this->getUser();
        /*
                $link = new Link();
                $link->setUrl(
                    'https://akwam.co/episode/15143/ratched-%D8%A7%D9%84%D9%85%D9%88%D8%B3%D9%85-%D8%A7%D9%84%D8%A7%D9%88%D9%84/%D8%A7%D9%84%D8%AD%D9%84%D9%82%D8%A9-6'
                );
                $link->setHostName(Link::HOST_AKWAM);
                $vLink=$this->generateVideoLink($link);
        */
        //$publicLinks = new ArrayCollection($this->getDoctrine()->getRepository(Link::class)->findUserPublicLinks($user));
        $links = $this->getDoctrine()->getRepository(Link::class)
            ->findUserLinks($user);
        foreach ($links as $l){
            $l->setUrl($this->generateVideoLink($l));
        }

        return $this->render('linker/linker_private_page.html.twig', [
            'links' => $links,
        ]);

    }

    private function generateVideoLink($link){
        if ($link->getHostName() === Link::HOST_AKWAM){
            return $this->genreateAkwamLink($link);
        }

        return null;
    }

    private function genreateAkwamLink($link){
        $response = $this->client->request(
            'GET',
            $link->getUrl()
        );
        $responseContent = $response->getContent();
        $needCaption = "http://goo-2o.com/link/";
        $page2Url=null;

        //if link is not a direct download page
        if (str_contains($responseContent, $needCaption)){
            dump('indirect link');
            //find video link location
            $page1Pos = strpos($responseContent, $needCaption );
            //get goo page
            $gooUrl = substr( $responseContent, $page1Pos ,28);
            //make request too goo page to get the v link
            $response = $this->client->request(
                'GET',
                $gooUrl
            );
            $responseContent = $response->getContent();
            $needCaption = "https://akwam.co";

            $page2Pos = strpos($responseContent, $needCaption );
            //get the line that contains the video link
            $page2ContentArea = substr( $responseContent, $page2Pos ,200);
            //find the position if beginning of the link
            $page2ContentStartPos= strpos($page2ContentArea, "ht" );
            //get the rest of the link body
            $page2ContentArea2= substr( $page2ContentArea, $page2ContentStartPos ,200);
            //find the position of the end of the link
            $page2ContentEndPos = strpos($page2ContentArea2, "\"" );
            //getting the complete link
            $page2Url= substr( $page2ContentArea2, 0 ,$page2ContentEndPos);
        }

        if (null !== $page2Url){
            $response = $this->client->request(
                'GET',
                $page2Url
            );
            $responseContent = $response->getContent();

        }
        $needCaption = "btn-loader";

        //find video link location
        $contentPos = strpos($responseContent, $needCaption);
        //get the line that contains the video link
        $linkContent1 = substr( $responseContent, $contentPos ,200);
        //find the position if beginning of the link
        $contentPos2 = strpos($linkContent1, "ht" );
        //get the rest of the link body
        $linkContent2= substr( $linkContent1, $contentPos2 ,200);
        //find the position of the end of the link
        $contentPos3 = strpos($linkContent2, "\"" );
        //getting the complete link
        $linkContent2= substr( $linkContent2, 0 ,$contentPos3);
        return $linkContent2;
    }

}
