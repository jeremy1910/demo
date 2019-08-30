<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 29/08/19
 * Time: 20:20
 */

namespace App\Service\History;


use App\Entity\History\HistorySearchArticle;
use App\Repository\CategoryRepository;
use App\Repository\HistorySearchArticleRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class HistorySearchArticleService extends HistorySearchAbstractService
{

    private $historySearchArticle;
    private $entityManager;
    private $parameterBag;
    private $historySearchArticleRepository;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag, HistorySearchArticleRepository $historySearchArticleRepository)
    {

        $this->historySearchArticle = new HistorySearchArticle($this);
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;
        $this->historySearchArticleRepository = $historySearchArticleRepository;

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

    public function purgeHistory(){
        $maxConservation = $this->parameterBag->get('max_keep_article_history');
        $numberOfRecords = $this->historySearchArticleRepository->getNumberOfRecords();

        if ($numberOfRecords >= $maxConservation){

            $delta = $numberOfRecords - $maxConservation;

            for ($i = $delta ; $i < $maxConservation; $i++)
            {
                $lastline = $this->historySearchArticleRepository->FindMostOlderRecord();
                $this->entityManager->remove($lastline);
            }
        }


    }
}