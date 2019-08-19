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

    public function searchAll($getSearchString)
    {
        $this->createQueryBuilder('p')
            ->addSelect("MATCH_AGAINST (p.name, :searchterm ) as score")
            ->addSelect("MATCH_AGAINST (p.description_s, :searchterm ) as score1")
            ->addSelect("MATCH_AGAINST (p.description_l, :searchterm ) as score2")
            ->andWhere('MATCH_AGAINST(p.name, :searchterm) > 0')
            ->orWhere('MATCH_AGAINST(p.description_s, :searchterm) > 0')
            ->orWhere('MATCH_AGAINST(p.description_l, :searchterm) > 0')
            ->setParameter('searchterm', "pompier")
            ->orderBy('score+score1*0.5+score2*0.3', 'desc')
            ->getQuery()
            ->getResult();
    }
}