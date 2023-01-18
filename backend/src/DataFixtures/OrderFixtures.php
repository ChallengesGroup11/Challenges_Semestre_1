<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $customers = $manager->getRepository(Customer::class)->findAll();

        for ($i = 0; $i < 10; $i++) {
            $object = (new Order())
                ->setDatetime($faker->dateTime())
                ->setCustomer($faker->randomElement($customers))
                ->setTotal(0)
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CustomerFixtures::class
        ];
    }
}
