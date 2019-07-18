<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByCondition($conditions, $maxResult = NULL, $offset = NULL, $count = false)
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
                if ($key == 'id') {
                    $req->andWhere('a.id LIKE :id')
                        ->setParameter('id', $condition);
                } else if ($key == 'username') {
                    $req->andWhere('a.username LIKE :username')
                        ->setParameter('username', '%'.$condition.'%');
                } else if ($key == 'adresseMail') {
                    $req->andWhere('a.adresseMail LIKE :adresseMail');
                    $req->setParameter('adresseMail', '%'.$condition.'%');
                } else if ($key == 'num_category') {
                    $req->andWhere($req->expr()->in('a.num_category', ':category'))
                        ->setParameter('category', $condition);
                } else if ($key == 'created_at_after') {
                    $req->andWhere('a.created_at >= :after')
                        ->setParameter('after', (new \DateTime($condition))->format('Y-m-d'));
                } else if($key == 'roles' AND  $condition != null){
                    $req->innerJoin('a.roles', 'r');
                    $req->andWhere('r.id = :roles')
                        ->setParameter('roles', $condition);
                }
                else if($key == 'enable' AND $condition != null){
                    $req->andWhere('a.enable = :enable')
                        ->setParameter('enable', $condition);
                }

            }
        }
        return $req->setFirstResult($offset)->setMaxResults($maxResult)->getQuery()
            ->getResult();

    }


    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
