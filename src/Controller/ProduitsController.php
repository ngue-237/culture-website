<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/produits", name="liste_produits")
     */
    public function listeProduits(ProduitsRepository $rep)
    {

        $produits = $rep->findAll();
        return $this->render('frontoffice/produits.twig', [
            'produits' => $produits,
        ]);
    }
}
