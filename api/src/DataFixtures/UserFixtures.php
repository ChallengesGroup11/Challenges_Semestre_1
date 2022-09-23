<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $pwd = '$2y$13$FkFAfT432e04Xa9j6Xesc.Qew/PgojVONELKFDSlVK2vi/TYOMwSK';

        $user = (new User())
            ->setEmail('user@user.fr')
            ->setPassword($pwd)
            ->setRoles(['ROLE_USER'])
        ;
        $manager->persist($user);

        $admin = (new User())
            ->setEmail('admin@user.fr')
            ->setPassword($pwd)
            ->setRoles(['ROLE_ADMIN'])
        ;
        $manager->persist($admin);

        $director = (new User())
            ->setEmail('manager@user.fr')
            ->setPassword($pwd)
            ->setRoles(['ROLE_DIRECTOR'])
        ;
        $manager->persist($director);

        $manager->flush();
    }
}
