<?php

namespace App\DataFixtures;

use App\Entity\Director;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DirectorFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $director = (new Director())
            ->setUserId($this->getReference(UserFixtures::DIRECTOR_REFERENCE))
            ->setDrivingSchoolId($this->getReference(DrivingSchoolFixtures::DRIVING_SCHOOL_REFERENCE));
        $manager->persist($director);

        for($i = 0 ; $i < 10 ; $i ++ ){
            $director = (new Director())
                ->setUserId($this->getReference(UserFixtures::DIRECTOR_REFERENCE_GROUP.$i))
                ->setDrivingSchoolId($this->getReference(DrivingSchoolFixtures::DRIVING_SCHOOL_REFERENCE_GROUP.$i));
            $manager->persist($director);
        }
        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            DrivingSchoolFixtures::class,
        ];
    }
}
