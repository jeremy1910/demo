<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 20/04/2019
 * Time: 00:10
 */

namespace App\Service;

use App\Controller\ArticleController;
use App\Entity\Tag;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use Symfony\Component\Security\Core\Security;

/**
 *
 * Class ArticleFilterHandler
 *
 * Cette classe permet de retoruner la liste des articles en fonction des critaires de recherche.
 *
 * @package App\Service
 *
 *
 */


class ArticleFilterHandler
{
    private const NB_ARTICLE_BY_PAGE = 12;

    private $tabCondition = [];
    private $numberArticle;
    private $numberPage;
    private $offset;
    private $pageActive;



    private $articleRepository;
    private $security;

    public function __construct(ArticleRepository $articleRepository, Security $security)
    {
        $this->articleRepository = $articleRepository;
        $this->security = $security;

    }

    public function setFilter(Request $request = null){

        if($request !== null) {
            if ($request->query->get('category')) {
                $this->tabCondition['num_category'] = $request->query->get('category');
            }
            if ($request->query->get('recherche')) {
                $this->tabCondition['title'] = '%' . $request->query->get('recherche') . '%';
            }

            if ($request->query->get('page')) {

                $this->pageActive = $request->query->get('page');
                $this->offset = self::NB_ARTICLE_BY_PAGE * ($this->pageActive - 1);

            } else {
                // Pas de pages spécifier => nous somme sur la page 1
                $this->offset = self::NB_ARTICLE_BY_PAGE * (1 - 1);
                $this->pageActive = 1;
            }
        }

        $this->numberArticle = $this->articleRepository->findNumberOfArticles($this->tabCondition);

        $this->numberPage = $this->calculNumberPage();

        return $this;

    }


    public function getResult(){
        return $this->articleRepository->findArticleByCondition2($this->tabCondition, self::NB_ARTICLE_BY_PAGE,  $this->offset);
    }


    public function getNumberArticle(){
        return $this->numberArticle;

    }


    public function getNumberPage()
    {
        return $this->numberPage;
    }

    public function getPageActive()
    {
        return $this->pageActive;
    }

    private function calculNumberPage(){


        $numberPage = round(ceil((int) $this->numberArticle[0][1] / self::NB_ARTICLE_BY_PAGE));
        if ($numberPage == 0)
        {
            $numberPage = 1;
        }
        return $numberPage;

    }

    public function articlesMapping(array $articles) :array
    {
        $tabArticles = [];


        /**
         * @var $article Article
         */

        foreach ($articles as $key => $article)
        {
            $tabArticles[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'body' => $article->getBody(),
                'numCategory' => $article->getNumCategory()->getLibele(),
                'createdAt' => $article->getCreatedAt()->format('d/m/Y H:i:s'),
                'lastEdit' => $article->getLastEdit() ? $article->getLastEdit()->format('d/m/Y H:i:s') : NULL,
                'image' => $article->getImage()->getFileName(),
                'description' => $article->getDescription(),
                'tags' => $this->formatTags($article->getTags()->toArray()),
                'canEdit' => $this->security->isGranted('EDIT', $article),
                'canDelete' => $this->security->isGranted('DELETE', $article),


            ];

        }
        return $tabArticles;

    }

    private function formatTags(array $array){

        $tab = [];

        /**
         * @var $tag Tag
         */
        foreach ($array as $tag)
        {
            $tab[] = $tag->getTagName();
        }
        return $tab;
    }

}