<?php

namespace App\DataFixtures;

use App\Entity\DrivingSchool;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DrivingSchoolFixtures extends Fixture
{

    public const DRIVING_SCHOOL_REFERENCE = 'driving-school';
    public const DRIVING_SCHOOL_REFERENCE_GROUP = 'driving-school-group';

    public function load(ObjectManager $manager): void
    {

        $drivingSchool = (new DrivingSchool())
                ->setName('Auto Ecole des Buisson')
                ->setAddress('Rue de la Joie')
                ->setZipcode('75005')
                ->setCity('Paris')
                ->setPhoneNumber('060000000')
                ->setStatus(true)
                ->setSiret('123456789');
            $manager->persist($drivingSchool);


        for($i =  0 ; $i < 10 ; $i ++ ){
            $drivingSchoolGroup = (new DrivingSchool())
                ->setName('Auto Ecole ' . $i)
                ->setAddress('Rue de la ' . $i)
                ->setZipcode('7500' . $i)
                ->setCity('Paris')
                ->setPhoneNumber('060000000' . $i)
                ->setStatus((bool)random_int(0, 1))
                ->setSiret('123456789' . $i);
            $manager->persist($drivingSchoolGroup);
            $this->addReference(self::DRIVING_SCHOOL_REFERENCE_GROUP.$i, $drivingSchoolGroup);
        }
        $manager->flush();

        $this->addReference(self::DRIVING_SCHOOL_REFERENCE, $drivingSchool);
    }
}
