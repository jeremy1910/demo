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
            ->getResult()
            ;
    }

    public function findNumberOfArticles(array $conditions = NULL)
    {

        if (sizeof($conditions) == 0)
        {
            return $this->createQueryBuilder('a')
                ->select('COUNT(a)')
                ->getQuery()
                ->getResult();
        }
        else{
            $req = $this->createQueryBuilder('a')
                ->select('COUNT(a)');

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

            return $req->getQuery()
                ->getResult();


        }
    }

    public function getByTags($tag){
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


    public function findArticleByCondition(array $conditions = NULL, $maxResult = NULL, $offset = NULL)
    {


        $req = $this->createQueryBuilder('a')
            ->select('a');

        foreach ($conditions as $key => $condition) {
            if ($key == 'author'){

                $req->innerJoin('a.user', 'user')
                    ->addSelect('user')

                    ->andWhere('user.username LIKE :user')
                    ->setParameter('user', '%'.$condition.'%');
            }
            else if ($key == 'tags'){


                $req->leftJoin('a.tags', 'tags')
                    ->andWhere($req->expr()->in('tags.tagName', ':tags'))
                    ->setParameter('tags', $condition);

            }
            else if ($key == 'title'){
                $req->andWhere('a.' . $key . ' LIKE :' . $key);
                $req->setParameter($key, '%'.$condition.'%');
            }
            else if ($key == 'category'){

                $req->andWhere($req->expr()->in('a.num_category', ':category'))
                    ->setParameter('category', $condition);
            }

            else if($key == 'created_after'){
                $req->andWhere('a.created_at >= :after')
                    ->setParameter('after', (new \DateTime($condition))->format('Y-m-d'));

            }
            else if($key == 'created_before'){

                $req->andWhere('a.created_at <= :before')
                    ->setParameter('before', (new \DateTime($condition))->format('Y-m-d'));

            }
            else{

                $req->andWhere('a.' . $key . ' = :' . $key);
                $req->setParameter($key, $condition);
            }
        }

        $querry = $req->orderBy('a.created_at', 'DESC')->setFirstResult($offset)->setMaxResults($maxResult)->getQuery();


        return $querry->getResult();

    }



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
    public function findRangeArticles($numberArticle, $offset)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM article a LIMIT 0, 12';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['numberArticle' => $numberArticle, 'offsett' => $offset]);
        return $stmt->fetchAll();
    }

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
