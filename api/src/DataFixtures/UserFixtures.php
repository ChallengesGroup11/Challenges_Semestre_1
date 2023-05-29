<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';
    public const USER_REFERENCE_GROUP = 'user-group';

    public const DIRECTOR_REFERENCE = 'user-director';
    public const DIRECTOR_REFERENCE_GROUP = 'udirector-group';

    public const MONITOR_REFERENCE = 'user-monitor';
    public const MONITOR_REFERENCE_GROUP = 'monitor-group';
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $pwd = '$2y$13$FkFAfT432e04Xa9j6Xesc.Qew/PgojVONELKFDSlVK2vi/TYOMwSK';

        $user = (new User())
            ->setFirstname('User')
            ->setLastname('UserLast')
            ->setToken('123456789')
            ->setStatus(true)
            ->setEmail('user@mail.fr')
            ->setPassword($pwd)
            ->setRoles(['ROLE_USER']);
        $manager->persist($user);


        $admin = new User();
        $admin->setFirstname('Admin');
        $admin->setLastname('AdminLast');
        $admin->setToken('123456789');
        $admin->setStatus(true);
        $admin->setEmail('admin@mail.fr');
        $password = $this->hasher->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $director = (new User())
            ->setFirstname('Director')
            ->setLastname('DirectorLast')
            ->setToken('123456789')
            ->setStatus(true)
            ->setEmail('director@mail.fr')
            ->setPassword($pwd)
            ->setRoles(['ROLE_DIRECTOR']);
        $manager->persist($director);

        $monitor = (new User())
            ->setFirstname('Monitor')
            ->setLastname('MonitorLast')
            ->setToken('123456789')
            ->setStatus(true)
            ->setEmail('maonitor@mail.fr')
            ->setPassword($pwd)
            ->setRoles(['ROLE_MONITOR']);
        $manager->persist($monitor);

        for ($i = 0; $i < 10; $i++) {
            $userGroup = (new User())
                ->setFirstname('User' . $i)
                ->setLastname('UserLast')
                ->setToken('123456789')
                ->setStatus(true)
                ->setEmail('user' . $i . '@mail.fr')
                ->setPassword($pwd)
                ->setRoles(['ROLE_USER']);
            $manager->persist($userGroup);
            $this->addReference(self::USER_REFERENCE_GROUP.$i, $userGroup);
        }

        for ($i = 0; $i < 10; $i++) {
            $directorGroup = (new User())
                ->setFirstname('Director' . $i)
                ->setLastname('DirectorLast')
                ->setToken('123456789')
                ->setStatus(true)
                ->setEmail('director' . $i . '@mail.fr')
                ->setPassword($pwd)
                ->setRoles(['ROLE_DIRECTOR']);
            $manager->persist($directorGroup);
            $this->addReference(self::DIRECTOR_REFERENCE_GROUP.$i, $directorGroup);
        }

        for ($i = 0; $i < 10; $i++) {
            $monitorGroup = (new User())
                ->setFirstname('Monitor' . $i)
                ->setLastname('MonitorLast')
                ->setToken('123456789')
                ->setStatus(true)
                ->setEmail('maonitor' . $i . '@mail.fr')
                ->setPassword($pwd)
                ->setRoles(['ROLE_MONITOR']);
            $manager->persist($monitorGroup);
            $this->addReference(self::MONITOR_REFERENCE_GROUP.$i, $monitorGroup);
        }

        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $user);
        $this->addReference(self::DIRECTOR_REFERENCE, $director);
        $this->addReference(self::MONITOR_REFERENCE, $monitor);

    }
}
