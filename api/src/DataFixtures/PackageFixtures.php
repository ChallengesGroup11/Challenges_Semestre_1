<?php

namespace App\DataFixtures;

use App\Entity\Package;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PackageFixtures extends Fixture
{

    const PACKAGE_REFERENCE_GROUP = 'package';
    public function load(ObjectManager $manager): void
    {

        for($i =0 ; $i < 3 ; $i ++){
            $package = (new Package)
                ->setName('Forfait ' . $i)
                ->setPrice(mt_rand(0, 1000))
                ->setNbCredit(mt_rand(0, 100))
                ->setDescription('Description ' . $i);
            $manager->persist($package);
            $manager->flush();
            $this->addReference(self::PACKAGE_REFERENCE_GROUP.$i, $package);
        }

    }


}
