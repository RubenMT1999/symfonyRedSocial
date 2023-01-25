<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserProfile;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProfile>
 *
 * @method UserProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfile[]    findAll()
 * @method UserProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfileRepository extends ServiceEntityRepository
{

    private $userRepository;

    public function __construct(ManagerRegistry $registry, UserRepository $userRepository)
    {
        parent::__construct($registry, UserProfile::class);
        $this->userRepository = $userRepository;
    }

    public function save(UserProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function guardarProfile($name, $bio, $website_url, $twitter_username,
                                    $company, $location, $date_of_birth, $usermail,$phone_number)
    {
        $newProfile = new UserProfile;

        $user = $this->userRepository->findOneBy(['email' => $usermail]);

        $fecha = new DateTime($date_of_birth);

        $newProfile
            ->setName($name)
            ->setBio($bio)
            ->setWebsiteUrl($website_url)
            ->setTwitterUsername($twitter_username)
            ->setCompany($company)
            ->setLocation($location)
            ->setDateOfBirth($fecha)
            ->setPhoneNumber($phone_number)
            ->setUser($user);
            
    
        $this->getEntityManager()->persist($newProfile);
        $this->getEntityManager()->flush();
    }


    public function updateProfile(UserProfile $profile) :UserProfile{

        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush();
        return $profile;
    }


    public function establecerProfileVacio(User $user){
        
        $nuevoProfile = new UserProfile;

        $nuevoProfile
            ->setName(null)
            ->setBio(null)
            ->setWebsiteUrl(null)
            ->setTwitterUsername($user->getEmail())
            ->setCompany(null)
            ->setLocation(null)
            ->setDateOfBirth(null)
            ->setUser($user);
        
        $user->setUserProfile($nuevoProfile);
    }

    /**
    * @return UserProfile[]
     */
    public function sugerirProfile(String $string): array{

        $entityManager = $this->getEntityManager();

        return $this->createQueryBuilder('u')
           ->andWhere('u.twitterUsername LIKE :val')
           ->setParameter('val', $string.'%')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;

    }



    /* public function findOneByUserEmail(User $usuario): ?UserProfile
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\UserProfile u
            WHERE u.user = :usuario'
        )->setParameter('usuario', $usuario);

        return $query->getOneOrNullResult();
    } */


//    /**
//     * @return UserProfile[] Returns an array of UserProfile objects
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

    /* public function findOneBySomeField($value): ?UserProfile
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    } */
}
