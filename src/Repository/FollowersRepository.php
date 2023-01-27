<?php

namespace App\Repository;

use App\Entity\Followers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Followers>
 *
 * @method Followers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Followers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Followers[]    findAll()
 * @method Followers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Followers::class);
        $this->manager = $manager;
    }

    public function save(Followers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Followers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeFollower(Followers $followers){
        $this->manager->remove($followers);
        $this->manager->flush();
    }

//    /**
//     * @return Followers[] Returns an array of Followers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
        public function findIdFollowers($value1, $value2): array {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id_emisor= :value1','f.id_receptor = :value2')
            ->setParameter('value1', $value1)
            ->setParameter('value2', $value2)
            ->getQuery()
            ->getResult();
        }

//    public function findOneBySomeField($value): ?Followers
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
