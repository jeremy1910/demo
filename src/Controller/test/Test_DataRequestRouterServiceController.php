<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 16/05/19
 * Time: 13:05
 */

namespace App\Controller\test;



use App\Entity\User;

use App\Service\DataRequest\DataRequestRouterServicesDataRequest;


use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;

class Test_DataRequestRouterServiceController extends AbstractController
{

    /**
     * @Route("/test", name="test")
     */
    public function main(Request $request){

        /**
         * @var UploadedFile $image
         */
        $image = $request->files->get('upload');

        /**
         * @var User $user
         */
        $user = $this->getUser();
        $username = $user->getUsername();
        $function_number = $request->query->get('CKEditorFuncNum');
        $imageName = md5(uniqid()) . '.' . $image->guessExtension();
        $directory = "/public/images/" . $username;
        $url = "/images/".$username."/".$imageName;
        $message = '';

        $image->move($this->getParameter('kernel.project_dir') . $directory, $imageName);

        return new Response("<script  type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('$function_number', '$url', '$message'); </script>");

    }


    /**
     * @Route("/test2", name="test2")
     */
    public function main2(Request $request)
    {

        $finder = new Finder();
        $filesystem = new Filesystem();

        $user = $this->getUser();
        $username = $user->getUsername();
        $directory = "/public/images/" . $username;
        $baseUrl = "/images/".$username."/";

        if(!$filesystem->exists($this->getParameter('kernel.project_dir') . $directory)){
            return $this->render('CKEditor/fileBrowser.html.twig', [
                'empty' => true,
            ]);
        }


        $finder->files()->in($this->getParameter('kernel.project_dir') . $directory);

        foreach ($finder as $file) {
            $fileNameWithExtension[] = $file->getRelativePathname();
        }

        array_splice($fileNameWithExtension, 20);
        return $this->render('CKEditor/fileBrowser.html.twig', [
            'files' => $fileNameWithExtension,
            'baseUrl' => $baseUrl,
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