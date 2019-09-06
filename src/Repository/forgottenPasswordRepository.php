<?php
/**
 * Created by PhpStorm.
 * User: jrambaud
 * Date: 06/09/2019
 * Time: 08:10
 */

namespace App\Repository;


use App\Entity\Security\ForgottenPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ForgottenPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForgottenPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForgottenPassword[]    findAll()
 * @method ForgottenPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class forgottenPasswordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ForgottenPassword::class);
    }
}