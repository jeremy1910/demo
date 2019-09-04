<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 16/05/19
 * Time: 13:05
 */

namespace App\Controller\test;


use App\Entity\Article;
use App\Form\AdminDashboard\ArticleDashboardFilterType;
use App\Service\DataRequest\DataRequestRouterServicesDataRequest;
use App\Service\History\HistorySearchArticleService;
use App\Service\Test\ServiceTest;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Test_DataRequestRouterServiceController extends AbstractController
{

    /**
     * @Route("/test", name="test")
     */
    public function main(){



        return $this->render('security/forgottenPasswordModalSucces.html.twig');

    }

    /**
     *
     * @param DataRequestRouterServicesDataRequest $dataRequestRouter
     * @param Request $request
     * @Route("/get_info", name="get_info")
     * @return Response
     */
    public function get_info(DataRequestRouterServicesDataRequest $dataRequestRouter, Request $request){


        $route = $dataRequestRouter->getRoute($request, 'GET');
        $serializer = SerializerBuilder::create()->build();
        if($route !== null){
            $route->setFilter();

            $route->handelMaxAndOffsetResult();
            $response = $route->getResult();
            $jsonContent = $serializer->serialize($response, 'json', SerializationContext::create()->enableMaxDepthChecks());

        }
        else{

            $response = false;
            $jsonContent = $serializer->serialize($response, 'json');
        }

        return new Response($jsonContent);
    }

}