<?php

namespace App\Repository;

use App\Entity\Megusta;
use Container5x7Xsgz\getMegustaRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

/**
 * @extends ServiceEntityRepository<Megusta>
 *
 * @method Megusta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Megusta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Megusta[]    findAll()
 * @method Megusta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MegustaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Megusta::class);
    }

    public function save(Megusta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Megusta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Megusta[] Returns an array of Megusta objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
    public function findPorLike(): array
    {
        return $this->createQueryBuilder('l')
            ->select('l , count(l.id_post) as veces')
            ->groupBy('l.id_post')
            ->setMaxResults(5)
            ->orderBy('veces', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPorLikeUser($value): array
    {
        return $this->createQueryBuilder('l')
            ->select('count(l.id_post) as veces')
            ->groupBy('l.id_post')
            ->andWhere('l.id_post = :val')
           ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }






}
