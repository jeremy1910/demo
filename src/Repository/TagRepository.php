<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findByCondition(array $conditions = NULL, $maxResult = NULL, $offset = NULL, $count = false)
    {
        if($count){
            $req = $this->createQueryBuilder('a')
                ->select('count(a.id)');
        }
        else {
            $req = $this->createQueryBuilder('a')
                ->select('a');
        }

        if($conditions !== NULL) {

            foreach ($conditions as $key => $condition) {
                if ($key == 'name') {
                    $req->andWhere('a.tagName LIKE :name');
                    $req->setParameter($key, '%' . $condition . '%');
                } else if ($key == 'id') {
                    $req->andWhere('a.id = :id');
                    $req->setParameter('id', $condition);
                } else {
                    return false;
                }
            }
        }
        return $req->setFirstResult($offset)->setMaxResults($maxResult)->getQuery()
            ->getResult();

    }
    // /**
    //  * @return Tag[] Returns an array of Tag objects
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
    public function findOneBySomeField($value): ?Tag
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
