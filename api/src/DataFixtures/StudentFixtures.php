<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class StudentFixtures extends Fixture implements DependentFixtureInterface
{
    public const STUDENT_REFERENCE = 'student';
    public const STUDENT_REFERENCE_GROUP = 'student';

    public function load(ObjectManager $manager): void
    {
        $student = (new Student())
            ->setUserId($this->getReference(UserFixtures::USER_REFERENCE))
            ->setNbHourDone(20)
            ->setCountCredit(4);
        $manager->persist($student);

        for($i = 0 ; $i < 10 ; $i ++ ){
            $studentGroup = (new Student())
                ->setUserId($this->getReference(UserFixtures::USER_REFERENCE_GROUP.$i))
                ->setNbHourDone(mt_rand(0, 40))
                ->setCountCredit(mt_rand(0,50));
            $manager->persist($studentGroup);
            $this->addReference(self::STUDENT_REFERENCE_GROUP.$i, $studentGroup);
        }
        $manager->flush();
        $this->addReference(self::STUDENT_REFERENCE, $student);

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
