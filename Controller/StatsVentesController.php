<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsVentesController extends AbstractController
{
    /**
     * @Route("admin/stats/ventes", name="admin_stat_vente")
     */
    public function index(): Response
    {
        return $this->render('backoffice/gestionVentes/stats/statVente.html.twig', [
            'controller_name' => 'StatsVentesController',
        ]);
    }
}
