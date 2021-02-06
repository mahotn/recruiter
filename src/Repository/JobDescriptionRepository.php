<?php

namespace App\Repository;

use App\Entity\JobDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobDescription[]    findAll()
 * @method JobDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobDescription::class);
    }

    // /**
    //  * @return JobDescription[] Returns an array of JobDescription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JobDescription
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
