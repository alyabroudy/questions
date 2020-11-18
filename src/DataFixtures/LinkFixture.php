<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\link;
use App\Entity\Question;
use App\Entity\QuestionGroup;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class LinkFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        /* // $product = new Product();
        // $manager->persist($product);
        $user = $manager->getRepository(User::class)->findAll();


        // create 20 products! Bam!
        for ($i = 0; $i < 6; $i++) {
            $link = new Link();
            $link->setName($faker->sentence);
            $link->setUrl($faker->imageUrl());
            $link->setFavorite($faker->boolean);
            $link->setPrivate($faker->boolean);
            $link->setRate($faker->randomFloat(1,0,10));

            $manager->persist($link);
            $user->addLink($link);
        }
        $manager->flush();
        */

    }
}
