<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function findAllUserDoneByDrivingSchool($id)
    {
        $qb = $this->createQueryBuilder('u')
          // user in student table , student in booking_student table , booking_student in booking table , booking in driving_school table
          // count le nombre de user par booking
            ->select('COUNT(bs.statusDone) as done')
            ->join('u.student', 's')
            ->join('s.bookings', 'bs')
            ->join('bs.drivingSchoolId', 'b')
            ->where('b.id = :id and bs.statusDone = true')
            ->setParameter('id', $id)
            ->groupBy('bs.statusDone')
            ->getQuery();

        return $qb->execute();
    }

    public function findAllUserValidateByDrivingSchool($id)
    {
        $qb = $this->createQueryBuilder('u')
          // user in student table , student in booking_student table , booking_student in booking table , booking in driving_school table
          // count le nombre de user par booking
            ->select('COUNT(bs.statusValidate) as validate')
            ->join('u.student', 's')
            ->join('s.bookings', 'bs')
            ->join('bs.drivingSchoolId', 'b')
            ->where('b.id = :id and bs.statusValidate = true')
            ->setParameter('id', $id)
            ->groupBy('bs.statusValidate')
            ->getQuery();

        return $qb->execute();
    }
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
