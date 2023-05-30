<?php

namespace App\Repository;

use App\Entity\Contratwithalbum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contratwithalbum>
 *
 * @method Contratwithalbum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contratwithalbum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contratwithalbum[]    findAll()
 * @method Contratwithalbum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratwithalbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contratwithalbum::class);
    }

    public function add(Contratwithalbum $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contratwithalbum $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Contratwithalbum[] Returns an array of Contratwithalbum objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Contratwithalbum
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
