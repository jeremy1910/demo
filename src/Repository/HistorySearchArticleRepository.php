<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 29/08/19
 * Time: 19:59
 */

namespace App\Repository;


use App\Entity\History\HistorySearchArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistorySearchArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistorySearchArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistorySearchArticle[]    findAll()
 * @method HistorySearchArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class HistorySearchArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistorySearchArticle::class);
    }


    public function getNumberOfRecords(){
        $query = $this->createQueryBuilder('h')
            ->select('COUNT(h.id)');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function FindMostOlderRecord(){
        $query = $this->createQueryBuilder('h')
            ->setMaxResults(1)
            ->orderBy('h.search_date');
        return $query->getQuery()->getResult()[0];

    }

    public function purgeOlderRecordsByDay($limitDayToDelete){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
          DELETE
          from history_search_article
          where DATEDIFF(CURDATE(), search_date) > :limitDayToDelete;
        ';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('limitDayToDelete', $limitDayToDelete);
        $stmt->execute();


    }


    public function getRecords($nb){

        $query = $this->createQueryBuilder('h')
            ->setMaxResults($nb)
            ->orderBy('h.search_date', 'desc');
        return $query->getQuery()->getResult();
    }
}


