<?php

namespace App\Controller;

use App\Repository\CommandeRepository;

use App\Repository\ProductCartRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

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
     * @Route("/admin/facutre/modifier_facture", name="admin_modifier_facture")
     */
    public function modifierFacture(){
        return $this->render('backoffice/gestionVentes/factures/modifier_facture.html.twig');
    }

    /**
     * @Route("/admin/facture_download/{id}", name="facture_download")
     */
    public function factureDownload($id, ProduitsRepository $repProd, CommandeRepository $repCmd, ProductCartRepository $rep, EntityManagerInterface $em){
        $commande = $repCmd->find($id);

        $query = $em->createQuery("select s From APP\Entity\ProductCart s where s.idCommande = :id")
            ->setParameter("id", $id);
        $productCart = $query->getResult();

        $produits = [];

        for ( $i =0; $i< count($productCart); $i++ ){
            $produits[] = [
                'produit'=>$repProd->find($productCart[$i]->getIdProduit()),
                'quantite'=>$productCart[$i]->getQuantite()
            ];
        }
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $context = stream_context_create([
            'ssl'=>[
                    'verify_peer'=>FALSE,
                    'verify_peer_name'=>FALSE,
                    'allow_self_signed'=>TRUE
            ]
        ]);
        $dompdf->setHttpcontext($context);
        $html = $this->renderView('backoffice/gestionVentes/factures/download_facture.html.twig', [
            "commande"=>$commande,
            "productCart"=>$productCart,
            "produits"=>$produits
        ]);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        //$fichier= 'facture'.$this->getUser()->getId().'pdf';
        $fichier= 'facture.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($fichier,[
            'Attachement'=>true
        ]);
        return new Response();
    }
}
