<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class IngredientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 40; $i++) {
            $object = (new Ingredient())
                ->setName($faker->name());

            $manager->persist($object);
        }

        $manager->flush();
    }
}
