<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 19/08/19
 * Time: 23:43
 */

namespace App\Repository;


use App\Entity\GeneralSearch\Generalsearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Generalsearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Generalsearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Generalsearch[]    findAll()
 * @method Generalsearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class GeneralSearchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Generalsearch::class);
    }


}