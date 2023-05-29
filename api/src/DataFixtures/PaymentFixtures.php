<?php

namespace App\DataFixtures;

use App\Entity\Payment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {


        for($i = 0 ; $i < 20 ; $i ++ ){
            $payementGroup = (new Payment())
                ->setPackageId($this->getReference(PackageFixtures::PACKAGE_REFERENCE_GROUP. mt_rand(0, 2)))
                ->setUserId($this->getReference(UserFixtures::USER_REFERENCE_GROUP.(mt_rand(0, 9))))
                ->setDateAction(new \DateTime());
            $manager->persist($payementGroup);
            $manager->flush();
        }

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            PackageFixtures::class,
        ];
    }
}
