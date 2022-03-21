<?php

namespace App\Controller;

use App\Entity\Produits;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProduitsRepository;

class AdminDashboardController extends AbstractController
{
    

    /**
     * @Route("/admin/dashboard", name="admin_prd")
     */
    public function admin_prd(ProduitsRepository $rep): Response{
        $produitTrité =$rep->ProduitsTritée();
        $produitNonTrité =$rep->ProduitsTritée();
        $Chart1 = new PieChart();
        $Chart1->getData()->setArrayToDataTable(
            [['Task', 'Hours per Day'],
                ['Produit Tritée',((int) $produitTrité)],
                ['Produit non Tritée',((int) $produitNonTrité)],
            ]
        );
        $Chart1->getOptions()->setTitle("le prix des produits");
        $Chart1->getOptions()->setHeight(400);
        $Chart1->getOptions()->setIs3D(2);
        $Chart1->getOptions()->setWidth(550);
        $Chart1->getOptions()->getTitleTextStyle()->setBold(true);
        $Chart1->getOptions()->getTitleTextStyle()->setColor('#009900');
        $Chart1->getOptions()->getTitleTextStyle()->setItalic(true);
        $Chart1->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $Chart1->getOptions()->getTitleTextStyle()->setFontSize(15);
        //dd($Chart1);
        return $this->render('backoffice/dashboard.html.twig',
            array('Chart1'=>$Chart1));
    }
}