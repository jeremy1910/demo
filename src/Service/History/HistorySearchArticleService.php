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
use Symfony\Component\Security\Core\Security;


class HistorySearchArticleService extends HistorySearchAbstractService
{

    private $historySearchArticle;
    private $entityManager;
    private $parameterBag;
    private $historySearchArticleRepository;
    private $security;
    private $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag, HistorySearchArticleRepository $historySearchArticleRepository, Security $security, CategoryRepository $categoryRepository)
    {

        $this->historySearchArticle = new HistorySearchArticle($this);
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;
        $this->historySearchArticleRepository = $historySearchArticleRepository;
        $this->security = $security;
        $this->categoryRepository = $categoryRepository;

    }


    public function createSearchHistory($search){

        $this->map($search);
        $this->historySearchArticle->setSearchDate(new \DateTime());
        $this->historySearchArticle->setByUser($this->security->getUser());
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
                    $this->historySearchArticle->setCreatedBefore(new \DateTime($element));
                    break;
                case 'created_at_after' :
                    $this->historySearchArticle->setCreatedAfter($element);
                    break;
                case 'tags':
                    foreach ($element as $tag){
                        $this->historySearchArticle->setTag($this->historySearchArticle->getTag() === null ? $tag : $this->historySearchArticle->getTag().','.$tag);
                    }
                    break;
                case 'num_category':
                    foreach ($element as $categoryID){
                        $category = $this->categoryRepository->find($categoryID);
                        $this->historySearchArticle->setCategory($this->historySearchArticle->getCategory() === null ? $category->getLibele() : $this->historySearchArticle->getCategory().','.$category->getLibele());
                    }
                    break;
                default:
                    break;

            }
        }

    }

    public function purgeHistory(){
        $maxConservation = $this->parameterBag->get('max_keep_article_history');

        $this->historySearchArticleRepository->purgeOlderRecordsByDay($maxConservation);
    }
}