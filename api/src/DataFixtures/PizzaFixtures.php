<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Pizza;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PizzaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $ingredients = $manager->getRepository(Ingredient::class)->findAll();

        for ($i = 0; $i < 10; $i++) {
            $object = (new Pizza())
                ->setName($faker->name())
                ->setDescription($faker->paragraph);

            for ($y = 0; $y < $faker->numberBetween(3, 8); $y++) {
                $object->addIngredient($faker->randomElement($ingredients));
            }

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            IngredientFixtures::class
        ];
    }
}
