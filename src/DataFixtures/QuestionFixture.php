<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuestionGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class QuestionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        // $product = new Product();
        // $manager->persist($product);
     /*   $qg1= new QuestionGroup();
        $qg1->setName('group 1');
        $qg1->setDescription($faker->sentence);

        $previous= null;

        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {


            $question = new Question();
            $question->setTitle($faker->sentence.' ?');
            $question->setQuestionGroup($qg1);

            for ($a = 0; $a < 3; $a++)
            {

                $answer = new Answer();
                $answer->setTitle($faker->sentence);
                $answer->setIsCorrect($faker->boolean);
                $question->addAnswer($answer);
                $manager->persist($answer);
            }
            if($i > 0){
                $previous->setNext($question);
                $manager->persist($previous);
            }
            $question->setPrevios($previous);
            $previous= $question;

            $manager->persist($question);
            $qg1->addQuestion($question);
        }
        $manager->persist($qg1);

        $manager->flush();
     */
    }
}
