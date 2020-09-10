<?php
/**
 * Created by PhpStorm.
 * User: mohammad.alyabroudy
 * Date: 7/17/2020
 * Time: 2:33 PM
 */
namespace App\tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class PayPalTest extends WebTestCase
{
    private $username="ASru5twZLTEA4mbTXL84pGLeVnTjhQ3BVzeDl_D8UlXrUEzgMj0QrqQcyoVS6-q8vlY8PPo-6cSxm4Jk";
    private $passord= "EItlVP8DXa65V0HkGsPU762xj_d4--GTKRd9W4HumYck3BRGFtftYL7rlvzJfqB30GEA-M6qIMTXvx-X";

   // public function getAccessToken($username, $password)
    /** @test */
    public function getAccessTokenTest()
    {

        $client = self::createClient(['grant_type = client_credentials'],['Accept'=>'application/json',
            'Accept-Language'=>'en_US',]);
        dd($this->username);
        $client->request(
            'Post',
            'https://api.sandbox.paypal.com/v1/oauth2/token',
            [
                'body'=> json_encode([
                    'username'=>$this->username,
                    'password'=>$this->passord
                ])
            ],
            array(),
            [
                'Accept'=>'application/json',
                'Accept-Language'=>'en_US',
            ],
            'grant_type = client_credentials'
            ,
            true
        );

        dd($client->getResponse());

    }
}