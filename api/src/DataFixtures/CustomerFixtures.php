<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 40; $i++) {
            $object = (new Customer())
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName)
                ->setPhone($faker->phoneNumber)
                ->setAddress($faker->address)
                ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}
