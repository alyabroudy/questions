<?php

namespace App\DataFixtures;

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
        $userAdmin->setEmail('admin@gmail.com');
        $userAdmin->setPassword('$argon2id$v=19$m=65536,t=4,p=1$33GifTrJkx6j2mHLDx4KOA$3/gfQqxPiS+PqZ3y/m7kCOpdechTwcpZ6d3g13jqkH0');
        $userAdmin->setRoles(['ROLE_ADMIN']);

        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$33GifTrJkx6j2mHLDx4KOA$3/gfQqxPiS+PqZ3y/m7kCOpdechTwcpZ6d3g13jqkH0');

        $manager->persist($userAdmin);
        $manager->persist($user);
        $manager->flush();
    }
}
