<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\PurchaseProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        // $product = new Product();
        // $manager->persist($product);

        // create 20 products! Bam!
        for ($i = 0; $i < 11; $i++) {
            $purchaseProduct = new PurchaseProduct();
            $product = new Product();
            $product->setName($faker->sentence);
            $product->setDescription($faker->realText());
            $product->setImage('p'.random_int(1,4));
            $product->setPrice($faker->randomFloat(3, 100, 350));

            $purchaseProduct->setProduct($product);
            $purchaseProduct->setQuantity(7);
            $manager->persist($product);
            $manager->persist($purchaseProduct);
        }
        $manager->flush();
    }
}
