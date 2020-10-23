<?php

namespace App\Repository;

use App\Entity\Relation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Relation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relation[]    findAll()
 * @method Relation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relation::class);
    }

    // /**
    //  * @return Relation[] Returns an array of Relation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findRelationsOfUserByStatus(User $user, $status)
    {
        $qb = $this->createQueryBuilder('r');
           $result = $qb->andWhere($qb->expr()->andX(
               $qb->expr()->eq('r.user',':val'),
               $qb->expr()->eq('r.status',':val2')
           ))
            ->setParameter('val', $user->getId())
            ->setParameter('val2', $status)
            ->orderBy('r.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
        return $result;
    }

    /*
    public function findOneBySomeField($value): ?Relation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
