<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Post;
use App\Entity\Question;
use App\Entity\QuestionGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        // $product = new Product();
        // $manager->persist($product);

        // create 20 products! Bam!
        for ($i = 0; $i < 6; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence);
            $post->setDescription($faker->realText());
            $post->setContent($faker->text);
            $post->setImage('uploads/'.$i);
            $post->setCreatedAt($faker->dateTime);

            $manager->persist($post);
        }
        $manager->persist($post);

        $manager->flush();
    }
}
