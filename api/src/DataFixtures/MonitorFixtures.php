<?php

namespace App\DataFixtures;

use App\Entity\Monitor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MonitorFixtures extends Fixture implements DependentFixtureInterface
{

    public const MONITOR_REFERENCE = 'monitor';
    public const MONITOR_REFERENCE_GROUP = 'monitor';
    public function load(ObjectManager $manager): void
    {
        $monitor = (new Monitor())
            ->setUserId($this->getReference(UserFixtures::MONITOR_REFERENCE))
            ->setDrivingSchoolId($this->getReference(DrivingSchoolFixtures::DRIVING_SCHOOL_REFERENCE));
        $manager->persist($monitor);

        for($i = 0 ; $i < 10 ; $i ++ ){
            $monitorGroup = (new Monitor())
                ->setUserId($this->getReference(UserFixtures::MONITOR_REFERENCE_GROUP.$i))
                ->setDrivingSchoolId($this->getReference(DrivingSchoolFixtures::DRIVING_SCHOOL_REFERENCE_GROUP.$i));
            $manager->persist($monitorGroup);
            $this->addReference(self::MONITOR_REFERENCE_GROUP.$i, $monitorGroup);
        }
        $manager->flush();
        $this->addReference(self::MONITOR_REFERENCE, $monitor);

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            DrivingSchoolFixtures::class,
        ];
    }
}
