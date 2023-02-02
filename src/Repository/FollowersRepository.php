<?php

namespace App\Repository;

use App\Entity\Followers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;


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
            ->select('f.id')
            ->andWhere('f.id_emisor= :value1 and f.id_receptor = :value2')
            ->setParameter('value1', $value1)
            ->setParameter('value2', $value2)
            ->getQuery()
            ->getResult();
        }

//        public function finIdFollowers($value1,$value2): array {
//
//        $rsm = new ResultSetMapping();
//
//        $query = $this->getEntityManager()->createNativeQuery('SELECT id from followers where id_emisor_id = ? and id_receptor_id = ?', $rsm);
//        $query->setParameter(1, $value1);
//        $query->setParameter(2, $value2);
//
//        $idFollowers = $query->getResult();
//
//
//        return $idFollowers;
//        }



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
