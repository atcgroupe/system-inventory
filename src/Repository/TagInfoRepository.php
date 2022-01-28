<?php

namespace App\Repository;

use App\Entity\TagInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TagInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagInfo[]    findAll()
 * @method TagInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagInfo::class);
    }

    // /**
    //  * @return TagInfo[] Returns an array of TagInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TagInfo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
