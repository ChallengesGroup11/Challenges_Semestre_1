<?php

namespace App\DataFixtures;

use App\Entity\Detail;
use App\Entity\Order;
use App\Entity\Pizza;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DetailFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $pizzas = $manager->getRepository(Pizza::class)->findAll();
        $orders = $manager->getRepository(Order::class)->findAll();

        for ($i = 0; $i < 200; $i++) {
            $object = (new Detail())
                ->setOrderDelivery($faker->randomElement($orders))
                ->setPizza($faker->randomElement($pizzas))
                ->setPrice($faker->numberBetween(9, 14))
                ->setSize($faker->randomElement(['S', 'M', 'XL']))
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            OrderFixtures::class,
            PizzaFixtures::class
        ];
    }
}
