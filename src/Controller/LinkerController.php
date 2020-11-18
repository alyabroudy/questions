<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\Relation;
use App\Entity\User;
use App\Form\LinkType;
use App\Form\RegistrationFormType;
use App\Repository\RelationRepository;
use App\Security\LoginControllerAuthenticator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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
        /** @var User $user */
        $user = $this->getUser();
        $approvedRelations = $this->getDoctrine()->getRepository(Relation::class)
            ->findRelationsOfUser($user);

        $relationRequests = $this->getDoctrine()->getRepository(Relation::class)
            ->findRelationRequestOfUser($user);

        return $this->render('linker/linker_friendspage.html.twig', [
            'relations' => $approvedRelations,
            'relationRequests' => $relationRequests,
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
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <p class="message">Hello World!</p>
        <p>Hello Crawler!</p>
    </body>
    </html>

HTML;
        $xml = <<<'HTML'
<?xml version="1.0" encoding="UTF-8"?>
<entry
    xmlns="http://www.w3.org/2005/Atom"
    xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:yt="http://gdata.youtube.com/schemas/2007"
>
    <id>tag:youtube.com,2008:video:kgZRZmEc9j4</id>
    <yt:accessControl action="comment" permission="allowed"/>
    <yt:accessControl action="videoRespond" permission="moderated"/>
    <media:group>
        <media:title type="plain">Chordates - CrashCourse Biology #24</media:title>
        <yt:aspectRatio>widescreen</yt:aspectRatio>
    </media:group>
</entry>
HTML;

        $crawler = new Crawler($html);
        $crawlerX = new Crawler($xml);

        foreach ($crawler as $domElement) {
            dump($domElement);
        }

        //Get the same level nodes after or before the current selection:
        //$crawler->filter('body > p')->nextAll();

       // Get all the child or parent nodes:
//$crawler->filter('body')->children();
//$crawler->filter('body > p')->parents();
        //// avoid the exception passing an argument that text() returns when node does not exist
        //$message = $crawler->filterXPath('//body/p')->text('Default text content');
        //// by default, text() trims white spaces, including the internal ones
        //// (e.g. "  foo\n  bar    baz \n " is returned as "foo bar baz")
        //// pass FALSE as the second argument to return the original text unchanged
        //$crawler->filterXPath('//body/p')->text('Default text content', false);

        //Access the attribute value of the first node of the current selection:
        //
        //$class = $crawler->filterXPath('//body/p')->attr('class');

        //Extract attribute and/or node values from the list of nodes:
        //
        //$attributes = $crawler
        //    ->filterXpath('//body/p')
        //    ->extract(['_name', '_text', 'class'])
        //;

        //links
        //// first, select the link by id, class or content...
        //$linkCrawler = $crawler->filter('#sign-up');
        //$linkCrawler = $crawler->filter('.user-profile');
        //$linkCrawler = $crawler->selectLink('Log in');
        //
        //// ...then, get the Link object:
        //$link = $linkCrawler->link();
        //
        //// or do all this at once:
        //$link = $crawler->filter('#sign-up')->link();
        //$link = $crawler->filter('.user-profile')->link();
        //$link = $crawler->selectLink('Log in')->link();
        //
        //The Symfony\Component\DomCrawler\Link object has several useful methods to get more information about the selected link itself:
        //
        //// returns the proper URI that can be used to make another request
        //$uri = $link->getUri();

        //$crawlerX->registerNamespace('m', 'http://search.yahoo.com/mrss/');
        //$crawlerX = $crawlerX->filterXPath('//m:group//yt:aspectRatio');
       // $crawlerX = $crawler->filter('default|entry media|group yt|aspectRatio');
       // $crawler = $crawler->filterXPath('//default:entry/media:group//yt:aspectRatio');
        dd($crawlerX);

        //reduce nodes
        /*
        $crawler = $crawler
            ->filter('body > p')
            ->reduce(function (Crawler $node, $i) {
                // filters every other node
                return ($i % 2) == 0;
            });
        */

        //dd($crawler);


        //$crawler = $crawler->filterXPath('descendant-or-self::body/p');
        //dd($crawler);
        //dd('done');

        //akwam search
        //$query = 'https://akwam.co/search?q=ratched&section=0&year=0&rating=0&formats=0&quality=0';
        $query = 'https://www.google.com/search?q=akwam.co ratched';
       //$this->searchAkwam($query);

/*
        $link = new Link();
        $link->setUrl(
            //'https://akwam.co/watch/37139/2586/s-o-s-survive-or-sacrifice'
            'http://cima4u.io/%d8%a7%d9%86%d9%85%d9%8a-%d9%88%d9%86-%d8%a8%d9%8a%d8%b3-one-piece-%d9%85%d8%aa%d8%b1%d8%ac%d9%85-%d8%ad%d9%84%d9%82%d8%a9-949/'
        );
        $link->setHostName(Link::HOST_AKWAM);
        //$vLink=$this->generateVideoLink($link);
/*
        //$publicLinks = new ArrayCollection($this->getDoctrine()->getRepository(Link::class)->findUserPublicLinks($user));
        $link = new Link();
        $link->setUrl(
       //     'https://w.cima4up.org/%d9%85%d8%b4%d8%a7%d9%87%d8%af%d8%a9-%d9%81%d9%8a%d9%84%d9%85-love-and-monsters-2020-%d9%85%d8%aa%d8%b1%d8%ac%d9%85-hd/'
            'http://cima4u.io/%d9%85%d8%b4%d8%a7%d9%87%d8%af%d8%a9-%d9%81%d9%8a%d9%84%d9%85-secret-weapon-2019-%d9%85%d8%aa%d8%b1%d8%ac%d9%85/'
        );
        $link->setHostName(Link::HOST_CINEMA4U);
        $vLink=$this->generateVideoLink($link);
*/

        $links = $this->getDoctrine()->getRepository(Link::class)
            ->findUserLinks($user);

       foreach ($links as $l){
            $l->setUrl($this->generateVideoLink($l));
        }


       // $this->findVideoLinks($link);
       //$this->genreateAkwamLink($links[0]);
        return $this->render('linker/linker_private_page.html.twig', [
            'links' => $links,
        ]);

    }

    public function searchAkwam($query){
        $response = $this->client->request(
            'GET',
            //"https://akwam.co/search?q=ratched&section=0&year=0&rating=0&formats=0&quality=0",
            $query,
            //$link->getUrl(),
            [
                'headers'=>['Content-Type'=> 'application/json']
            ]
        );
        //dump($link->getUrl());
        $responseContent = $response->getContent();
        dd($responseContent);
        $needCaption = "http://goo-2o.com/link/";
    }

    /**
     * get user Links
     * @Route("/addlink", name="add_link_page")
     */
    public function createLink(Request $request): Response
    {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $link->setName($form->get('name')->getData());
            $link->setUrl($form->get('url')->getData());
            $link->setHostName($form->get('hostName')->getData());
            $link->setPrivate($form->get('private')->getData());
            $link->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('private_page');
        }

        return $this->render('linker/linker_add_link_page.html.twig', [
            'linkForm' => $form->createView(),
        ]);
    }

    /**
     * get public Links of user friends
     * @Route("/search/{query}", name="search")
     */
    public function search(Request $request, $query= null)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (null === $query){
            $query = $request->request->get('query');
        }
        $resultUsers = $this->getDoctrine()->getRepository(User::class)->findByName($query);
        $resultLinks = $this->getDoctrine()->getRepository(Link::class)->findByName($query);
        //$publicLinks = new ArrayCollection($this->getDoctrine()->getRepository(Link::class)->findUserPublicLinks($user));

        return $this->render('linker/linker_search_result_page.html.twig', [
            'users' => $resultUsers,
            'links' => $resultLinks,
            'query' => $query
        ]);
    }

    private function generateVideoLink($link){
        switch ($link->getHostName()){
            case Link::HOST_AKWAM:
                return $this->genreateAkwamLink($link);
            case Link::HOST_CIMA4UP:
                return $this->generateCima4UpLink($link);
            case Link::HOST_CINEMA4U:
                return $this->generateCinema4ULink($link);

        }

        return null;
    }

    private function genreateAkwamLink($link){
        $response = $this->client->request(
            'GET',
            //"https://akwam.co/search?q=ratched&section=0&year=0&rating=0&formats=0&quality=0",
            $link->getUrl(),
            [
                'headers'=>['Content-Type'=> 'application/json']
            ]
        );
        //dump($link->getUrl());
        $responseContent = $response->getContent();

        $crawler = new Crawler($responseContent);
dd($crawler->filterXPath('contains(@src,mp4)'));
        foreach ($crawler as $domElement) {
            dump($domElement->nodeName);
        }

//dd($crawler->filter('a'));
dd('done');
        $needCaption = "http://goo-2o.com/link/";
        $page2Url=null;

        //if link is not a direct download page
        if (str_contains($responseContent, $needCaption)){
            dump('indirect link goo');
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
            //dump("page2content", $page2ContentArea);
            //find the position if beginning of the link
            $page2ContentStartPos= strpos($page2ContentArea, "ht" );
            //get the rest of the link body
            $page2ContentArea2= substr( $page2ContentArea, $page2ContentStartPos ,200);
            //find the position of the end of the link
            $page2ContentEndPos = strpos($page2ContentArea2, "\"" );
            //getting the complete link
            $page2Url= substr( $page2ContentArea2, 0 ,$page2ContentEndPos);
        }
        $needCaption = "http://aatfal.com/link/";
        //dump(str_contains($responseContent, $needCaption));
        if (str_contains($responseContent, $needCaption)){
            dump('indirect link aatfal');
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
//dump($page2Url);
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

    private function generateCima4UpLink($link)
    {
        $response = $this->client->request(
            'GET',
            $link->getUrl()
        );
        $responseContent = $response->getContent();

        dd($responseContent);
        $needCaption = "http://goo-2o.com/link/";
    }

    private function findVideoLinks($link)
    {
        $response = $this->client->request(
            'GET',
            $link->getUrl()
        );
        $responseContent = $response->getContent();

       // while (strlen($responseContent) > 5) {
            $needCaption = '.mp4';
            if (str_contains($responseContent, $needCaption)) {
                //1-find text position
                $endPos = 150;
                $tEnd = strpos($responseContent, $needCaption) + 4;//4 is the length of .mp4
                $tStart = $tEnd - $endPos;
                //2-extract text
                $text = substr($responseContent, $tStart, $endPos);
//dd(strlen($text), $tStart, $tEnd);
                //3-find link position
                $needCaption = 'http';
                $lStart = strpos($text, $needCaption);
                $link = substr($text, $lStart, strlen($text));

                $responseContent = substr($responseContent, $tEnd);
                dump($link, strlen($responseContent));
            }

    //    }
        dd('doesnot contains video');

    }


    private function generateCinema4ULink($link)
    {
        $response = $this->client->request(
            'GET',
            $link->getUrl()
        );
        $responseContent = $response->getContent();
        $needCaption = "http://live.cima4u.io/Video";
        if (str_contains($responseContent, $needCaption)) {
            dump('indirect link');
            //find video link location
            $page1Pos = strpos($responseContent, $needCaption);
            //get goo page
            $gooUrl = substr($responseContent, $page1Pos, 100);

            $page2ContentEndPos = strpos($gooUrl, "\"" );
            //get the rest of the link body
            $page2ContentArea2= substr( $gooUrl, 0, $page2ContentEndPos );

            $response = $this->client->request(
                'GET',
                $page2ContentArea2
            );
            dump($page2ContentArea2);
            $crawler = $this->client->clickLink('Login');

            dd($responseContent);
        }



    }

}
