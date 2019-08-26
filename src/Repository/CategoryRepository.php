<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[] Returns an array of Category objects
     */

    public function getAllLibele()
    {
        $req = $this->createQueryBuilder('a')
            ->select('a.id, a.libele');


        $query = $req->getQuery();
        return $query->getArrayResult();
    }

    public function findByCondition(array $conditions = NULL, $maxResult = NULL, $offset = NULL, $count = false){


        if($count){
            $req = $this->createQueryBuilder('a')
                ->select('count(a.id)');
        }
        else {
            $req = $this->createQueryBuilder('a')
                ->select('a');
        }

        if($conditions !== null) {
            foreach ($conditions as $key => $condition) {
                if ($key == 'id') {
                    $req->andWhere('a.id = :id')
                        ->setParameter('id', $condition);
                } else if ($key == 'libele') {


                    $req->andWhere('a.libele LIKE :libele')
                        ->setParameter('libele', '%' . $condition . '%');

                } else {
                    dd('requete invalide');
                }

            }
        }
        $querry = $req->orderBy('a.libele', 'DESC')->setFirstResult($offset)->setMaxResults($maxResult)->getQuery();

        return $querry->getResult();
    }

    public function getLastCreatedCategory(){
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.created_at', 'desc')
            ->setMaxResults(1);
        return $query->getQuery()->getResult();
    }

    public function getLastModifiedCategory(){
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.modified_at', 'desc')
            ->setMaxResults(1);
        return $query->getQuery()->getResult();
    }

    public function getNumberOfCategory(){
        $query = $this->createQueryBuilder('c')
            ->select("COUNT(c.id)");
        return $query->getQuery()->getSingleScalarResult();
    }

    /*
    public function findOneBySomeField($value): ?Category
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
