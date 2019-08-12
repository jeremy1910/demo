<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 16/05/19
 * Time: 12:56
 */

namespace App\Service\DataRequest;


use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class DataRequestRouterServicesDataRequest
{
    private $request;
    protected $target;
    protected $option;
    protected $em;
    protected $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {

        $this->em = $em;
        $this->security = $security;
    }

    public function getRoute(Request $request, string $type){

        if($type == "GET"){
            $this->request = $request->query;
        }elseif($type == "POST"){
            $this->request = $request->request;
        }
        else{
            dd('not good');
        }

        if($this->request->has('t')){
            $this->target = $this->request->get('t');

            $array = $this->request->all();

            array_shift($array);

            $this->option = $array;

            switch ($this->target) {
                case 'user':
                    return new DataRequestUserService($this->em, $this->target, $this->option, User::class);
                    break;
                case 'article':
                    return new DataRequestArticleService($this->em, $this->target, $this->option, Article::class, $this->security);
                    break;
                case 'category':
                    return new DataRequestCategoryService($this->em, $this->target, $this->option, Category::class);
                    break;
                case 'tag':
                    return new DataRequestTagService($this->em, $this->target, $this->option, Tag::class);
                    break;
                default:
                    dd('target incorect : '. $this->target);
            }
        }
        else{
            return null;
        }

    }



}