<?php

namespace App\Repository;

use App\Entity\Dislike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Dislike>
 *
 * @method Dislike|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dislike|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dislike[]    findAll()
 * @method Dislike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DislikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Dislike::class);
        $this->manager = $manager;
    }

    public function save(Dislike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Dislike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findIdDislike($value1, $value2): array {
        return $this->createQueryBuilder('d')
            ->select('d.id')
            ->andWhere('d.id_post= :value1 and d.id_user = :value2')
            ->setParameter('value1', $value1)
            ->setParameter('value2', $value2)
            ->getQuery()
            ->getResult();
    }

    public function addDislike(Dislike $dislike){
        $this->getEntityManager()->persist($dislike);
        $this->manager->flush();
    }

    public function removeDislike(Dislike $dislike){
        $this->manager->remove($dislike);
        $this->manager->flush();
    }

//    /**
//     * @return Dislike[] Returns an array of Dislike objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dislike
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
