<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 01/05/19
 * Time: 23:38
 */

namespace App\Controller\admin;


use App\Form\AdminDashboard\ArticleDashboardFilterType;
use App\Form\Filter\FilterCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_page")
     */
    public function admin_page()
    {

        $formArticle = $this->createForm(ArticleDashboardFilterType::class);
        $formCategory = $this->createForm(FilterCategoryType::class);
        return $this->render('admin/admin.html.twig', [
            'formArticle' => $formArticle->createView(),
            'formCategory' => $formCategory->createView(),
        ]);

    }

}