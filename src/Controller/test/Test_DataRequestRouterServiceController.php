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

        $form = $this->createForm(ArticleDashboardFilterType::class);

        return $this->render('test/view.html.twig', [
            'form' => $form->createView()
        ]);

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
        $response = $route->getResult();

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($response, 'json', SerializationContext::create()->enableMaxDepthChecks());


        return new Response($jsonContent);
    }

}