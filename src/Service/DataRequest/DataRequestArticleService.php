<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 17/05/19
 * Time: 23:42
 */

namespace App\Service\DataRequest;


use App\Entity\Article;
use App\Service\History\HistorySearchArticleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class DataRequestArticleService extends DataRequestClassService
{


    public function __construct(EntityManagerInterface $em, $target, $option, $entity, Security $security, HistorySearchArticleService $historySearchArticleService)
    {
        parent::__construct($em, $target, $option, $entity);
        $this->security = $security;
        $this->historySearchArticleService = $historySearchArticleService;

    }
    private $security;
    private $historySearchArticleService;
    protected $permitedOptions = ['title', 'user', 'tags[0-9]+', 'num_category[0-9]+', 'created_at_before', 'created_at_after', 'nbResult', 'pageSelected', 'content'];

    public function setFilter()
    {
        if ($this->option != []) {

            foreach ($this->option as $key => $line) {
                if (!$this->isOptionValide($key)) {
                    dd('option non valide : ' . $key);
                }

                if ($key == 'title') {
                    if (\preg_match("/[A-Za-z0-9]+/", $line)) {
                        $this->validedOptions['title'] = $line;
                    } else {
                        dd('argument username : ' . $line . ' invalide');
                    }
                } elseif ($key == 'content') {
                    if (\preg_match("/[A-Za-z0-9]+/", $line)) {
                        $this->validedOptions['content'] = $line;
                    } else {
                        dd('argument username : ' . $line . ' invalide');
                    }
                } elseif ($key == 'user') {
                    if (\preg_match("/[A-Za-z0-9]+/", $line)) {
                        $this->validedOptions['user'] = $line;
                    } else {
                        dd('argument username : ' . $line . ' invalide');
                    }
                } elseif (preg_match('/^num_category[0-9]+$/', $key)) {

                    if (\preg_match("/[A-Za-z0-9]+/", $line)) {
                        $this->validedOptions['num_category'][] = $line;
                    } else {
                        dd('argument category : ' . $line . ' category');
                    }
                } elseif (preg_match('/^tags[0-9]+$/', $key)) {

                    if (\preg_match("/[A-Za-z0-9]+/", $line)) {
                        $this->validedOptions['tags'][] = $line;
                    } else {
                        dd('argument username : ' . $line . ' invalide');
                    }
                } elseif ($key === 'created_at_before') {
                    if (\preg_match("/^(19[5-9][0-9]|20[0-4][0-9]|2050)[-](0?[1-9]|1[0-2])[-](0?[1-9]|[12][0-9]|3[01])$/", $this->option['created_at_before'])) {
                        $this->validedOptions['created_at_before'] = $this->option['created_at_before'];
                    } else {
                        dd('argument username : ' . $this->option['created_before'] . ' invalide');
                    }
                } elseif ($key === 'created_at_after') {
                    if (\preg_match("/^(19[5-9][0-9]|20[0-4][0-9]|2050)[-](0?[1-9]|1[0-2])[-](0?[1-9]|[12][0-9]|3[01])$/", $this->option['created_at_after'])) {
                        $this->validedOptions['created_at_after'] = $this->option['created_at_after'];
                    } else {
                        dd('argument username : ' . $this->option['created_after'] . ' invalide');
                    }
                }

            }
        }



    }

    public function getResult(){
        $repository = $this->em->getRepository($this->entity);
        if(!empty($this->validedOptions))
        {
            $this->historySearchArticleService->createSearchHistory($this->validedOptions);
        }
        $nbElement = $repository->findByCondition($this->validedOptions, null, null, TRUE);


        $result['result'] = $repository->findByCondition($this->validedOptions, $this->maxResult, ($this->offset-1)*$this->maxResult);

        /**
         * @var $article Article
         */

        foreach($result['result'] as $article){
            $result['canEdit'][$article->getId()] = $this->security->isGranted('ARTICLE_EDIT', $article);
            $result['canDelete'][$article->getId()] = $this->security->isGranted('ARTICLE_DELETE', $article);

        }

        $nbPage = (int) ceil($nbElement[0]['1'] / $this->maxResult);
        $result['nbPage'] = $nbPage;

        return $result;
    }



}