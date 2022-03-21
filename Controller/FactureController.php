<?php

namespace App\Controller;


use App\Repository\CommandeRepository;

use App\Repository\ProductCartRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    /**
     * @Route("/admin/facture", name="admin_facture")

    public function listeFacture(FactureRepository $repFact): Response
    {
        $factures = $repFact->findAll();
        return $this->render('backoffice/gestionVentes/factures/liste_factures.html.twig', [
            'controller_name' => 'FactureController',
        ]);
    }
     * /

    /**
     * @Route("/admin/facture/creer_facuture/{id}", name="facture_creer")
     *
    public function creerFacture($id, ProduitsRepository $repProd, CommandeRepository $repCmd, ProductCartRepository $rep, EntityManagerInterface $em){
        $commande = $repCmd->find($id);
        $facture = new facture();
        $facture->setCommande($commande);
        $em->persist($facture);
        $em->flush();
        $query = $em->createQuery("select s From APP\Entity\ProductCart s where s.idCommande = :id")
            ->setParameter("id", $id);
        $productCart = $query->getResult();

        dd($productCart[0]);

        $produits = [];

        for ( $i =0; $i< count($productCart); $i++ ){
            $produits[] = [
                'produit'=>$repProd->find($productCart[$i]->getIdProduit()),
                'quantite'=>$productCart[$i]->getQuantite()
            ];
        }
        //dd($produits);

        return $this->render('backoffice/gestionVentes/factures/facture.html.twig', [
            "commande"=>$commande,
            "productCart"=>$productCart,
            "produits"=>$produits
        ]);
    }*/

    /**
     * @Route("/admin/facutre/modifier_facture", name="admin_modifier_facture")
     */
    public function modifierFacture(){
        return $this->render('backoffice/gestionVentes/factures/modifier_facture.html.twig');
    }
}
