<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    /**
     * @Route("/admin/facture", name="admin_facture")
     */
    public function listeFacture(): Response
    {
        return $this->render('backoffice/gestionVentes/factures/liste_factures.html.twig', [
            'controller_name' => 'FactureController',
        ]);
    }

    /**
     * @Route("/admin/facture/creer_facuture", name="creer_facture")
     */
    public function creerFacture(){
        return $this->render('backoffice/gestionVentes/factures/creer_facture.html.twig');
    }

    /**
     * @Route("/admin/facutre/modifier_facture", name="admin_modifier_facture")
     */
    public function modifierFacture(){
        return $this->render('backoffice/gestionVentes/factures/modifier_facture.html.twig');
    }
}
