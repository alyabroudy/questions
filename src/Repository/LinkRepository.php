<?php

namespace App\Repository;

use App\Entity\Link;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Array_;

/**
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    public function findUserPublicLinks(User $user)
    {
        $qb = $this->createQueryBuilder('l');
        $relations= $user->getRelations();
        $links = array();

        foreach ($relations as $relation){
           $result = $qb->andWhere('l.user = :val')
                ->setParameter('val', $relation->getPartner()->getId())
                ->orderBy('l.id', 'ASC')
                //->setMaxResults(10)
                ->getQuery()
                ->getArrayResult()
            ;
            $links= array_merge((array) $links, (array) $result);
        }
        return $links;
    }

    public function findUserLinks(User $user)
    {
        $qb = $this->createQueryBuilder('l');

            $result = $qb->andWhere('l.user = :val')
                ->setParameter('val', $user->getId())
                ->orderBy('l.id', 'ASC')
                //->setMaxResults(10)
                ->getQuery()
                ->getResult()
                //->getArrayResult()
            ;

        return $result;
    }

    public function findUserFavoriteLinks(User $user)
    {
        $qb = $this->createQueryBuilder('l');
        $result = $qb->andWhere($qb->expr()->andX(
            $qb->expr()->eq('l.user',':val'),
            $qb->expr()->eq('l.favorite',':val2')
        ))
            ->setParameter('val', $user->getId())
            ->setParameter('val2', true)
            ->orderBy('l.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
        return $result;
    }

    // /**
    //  * @return Link[] Returns an array of Link objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Link
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
