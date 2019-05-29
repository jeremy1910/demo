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

    public function findUserByCondition(array $conditions = NULL, $maxResult = NULL, $offset = NULL)
    {


        $req = $this->createQueryBuilder('a')
            ->select('a');

        foreach ($conditions as $key => $condition) {
            if ($key == 'username' OR $key == 'adresseMail'){
                $req->andWhere('a.' . $key . ' LIKE :' . $key);
                $req->setParameter($key, '%'.$condition.'%');
            }
            else{
                $req->andWhere('a.' . $key . ' = :' . $key);
                $req->setParameter($key, $condition);
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
