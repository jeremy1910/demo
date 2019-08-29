<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 29/08/19
 * Time: 20:20
 */

namespace App\Service\History;


use App\Entity\History\HistorySearchArticle;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class HistorySearchArticleService extends HistorySearchAbstractService
{

    private $historySearchArticle;
    private $entityManager;
    private $parameterBag;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag)
    {

        $this->historySearchArticle = new HistorySearchArticle();
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;

    }


    public function createSearchHistory($search){

        $this->map($search);
        $this->historySearchArticle->setSearchDate(new \DateTime());
        $this->entityManager->persist($this->historySearchArticle);
        $this->entityManager->flush();
    }

    private function map($search){


        foreach ($search as $key => $element){
            switch ($key) {
                case 'content':
                    $this->historySearchArticle->setContent($element);
                    break;
                case 'user' :
                    $this->historySearchArticle->setAuthor($element);
                    break;
                case 'created_at_before' :
                    $this->historySearchArticle->setCreatedBefore($element);
                    break;
                case 'created_at_after' :
                    $this->historySearchArticle->setCreatedAfter($element);
                    break;
                case preg_match('/^tags[0-9]+$/', $key):
                    $this->historySearchArticle->setTag($this->historySearchArticle->getTag().','.$element);
                    break;


            }
        }

    }

    private function purgeHistory(){
        $maxConservation = $this->parameterBag->get('max_keep_article_history');


    }
}