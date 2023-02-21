<?php

namespace App\Repository;

use App\Entity\Like;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Like>
 *
 * @method Like|null find($id, $lockMode = null, $lockVersion = null)
 * @method Like|null findOneBy(array $criteria, array $orderBy = null)
 * @method Like[]    findAll()
 * @method Like[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry,  EntityManagerInterface $manager)
    {
        parent::__construct($registry, Like::class);
        $this->manager = $manager;
    }

    public function save(Like $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Like $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function addLike(Like $like){
        $this->getEntityManager()->persist($like);
        $this->manager->flush();
    }

    public function removeLike(Like $like){
        $this->manager->remove($like);
        $this->manager->flush();
    }

    public function findIdLike($value1, $value2): array {
        return $this->createQueryBuilder('l')
            ->select('l.id')
            ->andWhere('l.id_post= :value1 and l.id_user = :value2')
            ->setParameter('value1', $value1)
            ->setParameter('value2', $value2)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Like[] Returns an array of Like objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Like
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
