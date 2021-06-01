<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $user= new User();
         $user->setEmail('kelo.feu@gmail.com');
         $user->setRoles(['ROLE_USER']);

         $manager->persist($user);

        $manager->flush();
    }
}
