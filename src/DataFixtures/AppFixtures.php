<?php

namespace App\DataFixtures;

use App\Entity\Relation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // $product = new Product();
        // $manager->persist($product);
        $userAdmin = new User();
        $userAdmin->setEmail('admin2@gmail.com');
        $userAdmin->setPassword('$argon2id$v=19$m=65536,t=4,p=1$33GifTrJkx6j2mHLDx4KOA$3/gfQqxPiS+PqZ3y/m7kCOpdechTwcpZ6d3g13jqkH0');
        $userAdmin->setRoles(['ROLE_ADMIN']);

        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$33GifTrJkx6j2mHLDx4KOA$3/gfQqxPiS+PqZ3y/m7kCOpdechTwcpZ6d3g13jqkH0');

        $user2 = new User();
        $user2->setEmail('user2@gmail.com');
        $user2->setPassword('$argon2id$v=19$m=65536,t=4,p=1$33GifTrJkx6j2mHLDx4KOA$3/gfQqxPiS+PqZ3y/m7kCOpdechTwcpZ6d3g13jqkH0');

        $user3 = new User();
        $user3->setEmail('user3@gmail.com');
        $user3->setPassword('$argon2id$v=19$m=65536,t=4,p=1$33GifTrJkx6j2mHLDx4KOA$3/gfQqxPiS+PqZ3y/m7kCOpdechTwcpZ6d3g13jqkH0');


        $manager->persist($userAdmin);
        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->flush();

    }
}
