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
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT c.libele FROM App\Entity\Category c');

        return $query->execute();
    }

    public function findCategoryByCondition(array $conditions = NULL, $maxResult = NULL, $offset = NULL){
        $req = $this->createQueryBuilder('a')
            ->select('a');

        foreach ($conditions as $key => $condition) {
            if ($key == 'id'){
                $req->andWhere('a.id = :id')
                    ->setParameter('id', $condition);
            }
            else if ($key == 'libele'){


                $req->andWhere('a.libele LIKE :libele')
                    ->setParameter('libele', '%'.$condition.'%');

            }
            else{
                dd('requete invalide');
            }

        }
        $querry = $req->orderBy('a.libele', 'DESC')->setFirstResult($offset)->setMaxResults($maxResult)->getQuery();

        return $querry->getResult();
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
