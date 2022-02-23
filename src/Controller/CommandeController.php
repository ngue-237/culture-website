<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/admin/commande", name="admin_commande")
     */
     public function listeCommande(){

        return $this->render('backoffice/gestionVentes/commandes/liste_cmd.html.twig');
     }

     /**
     * @Route("/admin/commande_mofier", name="admin_cmd_modifier")
     */
    public function modifierCommande(){

        return $this->render('backoffice/gestionVentes/commandes/modifier_cmd.html.twig');
     }

     /**
      * @Route("/mes_commandes", name="mes_cmd")
      *
      * @return void
      */
     public function afficheCmd(){
      return $this->render('frontoffice/mescmd.html.twig');
     }

     /**
      * @Route("/passer_commande", name ="passer_cmd")
      */

      public function passerCmd(){
         return $this->render('frontoffice/commander.html.twig');
      }
}
