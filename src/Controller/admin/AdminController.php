<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 01/05/19
 * Time: 23:38
 */

namespace App\Controller\admin;


use App\Form\AdminDashboard\ArticleDashboardFilterType;
use App\Form\Article\Filter\ArticleFilterType;
use App\Form\Filter\FilterCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tag;
use App\Form\Filter\FilterTagType;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_page")
     */
    public function admin_page()
    {

        $formArticle = $this->createForm(ArticleFilterType::class, null, array(
            'action' => $this->generateUrl("articleFilter")));

        $formCategory = $this->createForm(FilterCategoryType::class);
        $formTag = $this->createForm(FilterTagType::class);
        return $this->render('admin/admin.html.twig', [
            'formArticle' => $formArticle->createView(),
            'formCategory' => $formCategory->createView(),
            'formTag' => $formTag->createView(),
        ]);

    }

}