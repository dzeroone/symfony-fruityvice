<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private LoggerInterface $logger)
    {
        parent::__construct($registry, Fruit::class);
    }

    public function save(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Fruit[] Returns an array of Fruit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fruit
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    function findFruits($currentPage = 1, $search = '', $user = null) {
        $dbQuery = "SELECT f, n FROM App\Entity\Fruit f JOIN f.nutrition n";
        if($user) {
            $dbQuery = "SELECT f, n, lf
                FROM App\Entity\Fruit f
                LEFT JOIN f.nutrition n
                LEFT JOIN f.likes lf WITH lf.user = {$user->getId()}
            ";
        }

        if($search) {
            $dbQuery = "SELECT f, n FROM App\Entity\Fruit f JOIN f.nutrition n WHERE LOWER(f.name) LIKE '$search%' OR LOWER(f.family) LIKE '$search%'";
            if($user) {
                $dbQuery = "SELECT f, n, lf.id IS NOT NULL AS liked
                    FROM App\Entity\Fruit f
                    LEFT JOIN f.nutrition n
                    LEFT JOIN f.likes lf WITH lf.user = {$user->getId()}
                    WHERE LOWER(f.name) LIKE '$search%' OR LOWER(f.family) LIKE '$search%'
                ";
            }
        }

        // $this->logger->info($dbQuery);

        $query = $this->getEntityManager()->createQuery($dbQuery);
        $paginator = new Paginator($query, true);
        $total = count($paginator);
        $paginator->getQuery()->setFirstResult(10 * ($currentPage - 1) )
            ->setMaxResults(10);
        
        $fruits = $paginator->getQuery()->getArrayResult();
        return [$total, $fruits];
    }
}
