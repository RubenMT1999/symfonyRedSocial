<?php

namespace App\Repository;

use App\Entity\Relio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Relio>
 *
 * @method Relio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relio[]    findAll()
 * @method Relio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Relio::class);
        $this->manager = $manager;
    }

    public function save(Relio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Relio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findIdRelio($value1, $value2): array {
        return $this->createQueryBuilder('r')
            ->select('r.id')
            ->andWhere('r.id_post= :value1 and r.id_user = :value2')
            ->setParameter('value1', $value1)
            ->setParameter('value2', $value2)
            ->getQuery()
            ->getResult();
    }

    public function addRelio(Relio $relio){
        $this->getEntityManager()->persist($relio);
        $this->manager->flush();
    }

    public function removeRelio(Relio $relio){
        $this->manager->remove($relio);
        $this->manager->flush();
    }

    public function findPorRelioeUser($value): array
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id_post) as veces')
            ->groupBy('r.id_post')
            ->andWhere('r.id_post = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function relioUsernum($value): array
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id_user) as veces')
            ->groupBy('r.id_user')
            ->andWhere('r.id_user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return Relio[] Returns an array of Relio objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Relio
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
