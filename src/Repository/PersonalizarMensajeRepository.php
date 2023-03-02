<?php

namespace App\Repository;

use App\Entity\PersonalizarMensaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonalizarMensaje>
 *
 * @method PersonalizarMensaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalizarMensaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalizarMensaje[]    findAll()
 * @method PersonalizarMensaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalizarMensajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonalizarMensaje::class);
    }

    public function save(PersonalizarMensaje $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PersonalizarMensaje $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PersonalizarMensaje[] Returns an array of PersonalizarMensaje objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PersonalizarMensaje
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
