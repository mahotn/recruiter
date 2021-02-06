<?php

namespace App\Repository;

use App\Entity\CompanyPicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompanyPicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyPicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyPicture[]    findAll()
 * @method CompanyPicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyPictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyPicture::class);
    }

    // /**
    //  * @return CompanyPicture[] Returns an array of CompanyPicture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompanyPicture
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
