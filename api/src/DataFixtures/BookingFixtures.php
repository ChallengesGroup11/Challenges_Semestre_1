<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Monitor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $booking = (new Booking)
            ->setDrivingSchoolId($this->getReference(DrivingSchoolFixtures::DRIVING_SCHOOL_REFERENCE))
            ->setSlotBegin(new \DateTime(sprintf('-%d days', rand(1, 100))))
            ->setSlotEnd(new \DateTime(sprintf('-%d days', rand(1, 100))))
            ->setStatusDone((bool)random_int(0, 1))
            ->setStatusValidate((bool)random_int(0, 1))
            ->setComment('Commentaire ')
            ->addStudentId($this->getReference(StudentFixtures::STUDENT_REFERENCE));
        $manager->persist($booking);
        $manager->flush();

        for ($i = 0; $i < 20; $i++) {
            $bookingGroup = (new Booking)
                ->setDrivingSchoolId($this->getReference(DrivingSchoolFixtures::DRIVING_SCHOOL_REFERENCE_GROUP . mt_rand(0, 9)))
                ->setSlotBegin(new \DateTime(sprintf('-%d days', rand(1, 100))))
                ->setSlotEnd(new \DateTime(sprintf('-%d days', rand(1, 100))))
                ->setStatusDone((bool)random_int(0, 1))
                ->setStatusValidate((bool)random_int(0, 1))
                ->setComment('Commentaire ' . $i)
                ->addStudentId($this->getReference(StudentFixtures::STUDENT_REFERENCE_GROUP . mt_rand(0, 9)));

            $manager->persist($bookingGroup);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            DrivingSchoolFixtures::class,
            StudentFixtures::class,
            MonitorFixtures::class,
        ];
    }
}
