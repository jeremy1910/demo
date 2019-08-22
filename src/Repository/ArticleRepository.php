<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of Article objects
     */

    public function find10lastArticles()
    {
        return $this->createQueryBuilder('a')
            ->setMaxResults(10)
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function find5lastArticles()
    {
        return $this->createQueryBuilder('a')
            ->setMaxResults(5)
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findNumberOfArticles(array $conditions = [])
    {

        if (sizeof($conditions) == 0) {
            return $this->createQueryBuilder('a')
                ->select('COUNT(a)')
                ->getQuery()
                ->getResult();
        } else {
            $req = $this->createQueryBuilder('a')
                ->select('COUNT(a)');

            foreach ($conditions as $key => $condition) {

                if ($key != 'title') {
                    $req->andWhere('a.' . $key . ' = :' . $key);
                    $req->setParameter($key, $condition);
                } else {
                    dump($key);
                    $req->andWhere('a.' . $key . ' LIKE :' . $key);
                    $req->setParameter($key, $condition);
                }
            }

            return $req->getQuery()
                ->getResult();


        }
    }

    public function getByTags($tag)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('a')
            ->from($this->_entityName, 'a');

        $query->leftJoin('a.tags', 'tags');


        $query = $query->add('where', $query->expr()->in('tags.tagName', ':tags'))
            ->setParameter('tags', $tag)
            ->groupBy('a.id')
            ->having('count(a.id) = 2')
            ->getQuery()
            ->getResult();

        return $query;
    }


    public function findByCondition($conditions, $maxResult = NULL, $offset = NULL, $count = false)
    {
        $orderByDate = true;
        if ($count) {
            $req = $this->createQueryBuilder('a')
                ->select('count(a.id)');
        } else {
            $req = $this->createQueryBuilder('a')
                ->select('a');
        }

        if ($conditions !== NULL) {
            foreach ($conditions as $key => $condition) {
                if ($key == 'user') {

                    $req->innerJoin('a.user', 'user')
                        //->addSelect('user.username')
                        ->andWhere('user.username LIKE :user')
                        ->setParameter('user', '%' . $condition . '%');

                } else if ($key == 'content') {
                    $req->andWhere("MATCH_AGAINST (a.title, :searchterm 'IN BOOLEAN MODE') > 0")
                        ->orWhere("MATCH_AGAINST (a.description, :searchterm 'IN BOOLEAN MODE') > 0")
                        ->orWhere("MATCH_AGAINST (a.body, :searchterm 'IN BOOLEAN MODE') > 0")
                        ->setParameter('searchterm', "*$condition*")
                        ->addOrderBy("(MATCH_AGAINST (a.title, :searchterm 'IN BOOLEAN MODE')) + ((MATCH_AGAINST (a.description, :searchterm 'IN BOOLEAN MODE'))*0.5) + ((MATCH_AGAINST (a.body, :searchterm 'IN BOOLEAN MODE'))*0.2)", 'desc');
                        $orderByDate = false;
                } else if ($key == 'tags') {


                    $req->leftJoin('a.tags', 'tags')
                        ->andWhere($req->expr()->in('tags.tagName', ':tags'))
                        ->setParameter('tags', $condition);

                } else if ($key == 'title') {
                    $req->andWhere('a.' . $key . ' LIKE :' . $key);
                    $req->setParameter($key, '%' . $condition . '%');
                } else if ($key == 'num_category') {

                    $req->andWhere($req->expr()->in('a.num_category', ':category'))
                        ->setParameter('category', $condition);
                } else if ($key == 'created_at_after') {
                    $req->andWhere('a.created_at >= :after')
                        ->setParameter('after', (new \DateTime($condition))->format('Y-m-d'));

                } else {
                    return [false, 'condition name incorect'];
                }
            }
        }

        if($orderByDate){
            $req->orderBy('a.created_at', 'DESC');
        }
        $querry = $req->setFirstResult($offset)->setMaxResults($maxResult)->getQuery();

        return $querry->getResult();

    }

    public function searchAll($getSearchString){
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT a.id, a.title, substring(a.description, 1, 50) AS description, c.libele, 
       MATCH(title) AGAINST(:searchterm IN BOOLEAN MODE)  AS  score_title,
       MATCH(description) AGAINST(:searchterm IN BOOLEAN MODE)  AS  score_description,
       MATCH(body) AGAINST(:searchterm IN BOOLEAN MODE)  AS  score_body
        FROM article as a
             inner join category c on c.id = a.num_category_id
        WHERE
        MATCH(title) AGAINST(:searchterm IN BOOLEAN MODE) OR
        MATCH(description) AGAINST(:searchterm IN BOOLEAN MODE) OR
        MATCH(body) AGAINST(:searchterm IN BOOLEAN MODE)
        ORDER BY (score_title + score_description * 0.5 + score_body * 0.1) DESC;';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue('searchterm', "''*$getSearchString*'");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /*
    public function searchAll($getSearchString)
    {
        $qb = $this->createQueryBuilder('p');
           $req =  $qb->select('p.id')
            ->addSelect('p.title')
            ->addSelect($qb->expr()->substring('p.description', 1, 50))

            ->addSelect("MATCH_AGAINST (p.title, p.description, p.body, :searchterm 'IN BOOLEAN MODE') as score")
            //->addSelect("MATCH_AGAINST (p.description, :searchterm ) as score1")
            //->addSelect("MATCH_AGAINST (p.body, :searchterm ) as score2")

            ->andWhere("MATCH_AGAINST (p.title, p.description, p.body, :searchterm 'IN BOOLEAN MODE') > 0")
            //->orWhere('MATCH_AGAINST(p.description, :searchterm) > 0')
            //->orWhere('MATCH_AGAINST(p.body, :searchterm) > 0')
            ->setParameter('searchterm', "*$getSearchString*")
            ->orderBy('score', 'desc')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
        return $req;
    }
*/
    public function findRangeArticles($numberArticle, $offset)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM article a LIMIT 0, 12';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['numberArticle' => $numberArticle, 'offsett' => $offset]);
        return $stmt->fetchAll();
    }

    /*
        public function findArticleByCondition2(array $conditions = NULL, $maxResult = NULL, $offset = NULL)
        {

            if (sizeof($conditions) == 0)
            {
                return $this->createQueryBuilder('a')
                    ->select('a')
                    ->setFirstResult($offset)->setMaxResults($maxResult)
                    ->orderBy('a.created_at', 'DESC')
                    ->getQuery()
                    ->getResult();
            }
            else{
                $req = $this->createQueryBuilder('a')
                    ->select('a');

                foreach ($conditions as $key => $condition) {

                    if ($key != 'title') {
                        $req->andWhere('a.' . $key . ' = :' . $key);
                        $req->setParameter($key, $condition);
                    }
                    else
                    {
                        dump($key);
                        $req->andWhere('a.' . $key . ' LIKE :' . $key);
                        $req->setParameter($key, $condition);
                    }
                }

                return $req->setFirstResult($offset)->setMaxResults($maxResult)->orderBy('a.created_at', 'DESC')->getQuery()
                    ->getResult();


            }
        }
        /*
     public function findNumberOfArticles($condition=NULL)
     {
         if ($condition == NULL)
         {
             return $this->createQueryBuilder('a')
                 ->select('COUNT(a)')
                 ->getQuery()
                 ->getResult();
         }
         else{
                 $req = $this->createQueryBuilder('a')
                 ->select('COUNT(a)');
                 $req->andWhere('a.num_category = :opt')
                 ->setParameter('opt', "$condition")
                 ->getQuery()
                 ->getResult();

                 return $req;
         }
     }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
