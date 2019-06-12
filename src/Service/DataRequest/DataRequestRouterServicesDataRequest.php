<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 16/05/19
 * Time: 12:56
 */

namespace App\Service\DataRequest;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DataRequestRouterServicesDataRequest
{
    private $request;
    protected $target;
    protected $option;
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;
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
                    return new DataRequestUserService($this->em, $this->target, $this->option);
                    break;
                case 'article':
                    return new DataRequestArticleService($this->em, $this->target, $this->option);
                    break;
                case 'category':
                    return new DataRequestCategoryService($this->em, $this->target, $this->option);
                    break;
                case 'tag':
                    return new DataRequestTagService($this->em, $this->target, $this->option);
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