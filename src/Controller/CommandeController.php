<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Commandes;
use App\Entity\ProductCart;
use App\Entity\Produits;
use App\Form\CommandesType;
use App\Form\ProductCartType;
use App\Repository\CommandeRepository;
use App\Repository\ProductCartRepository;
use App\Repository\ProduitsRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/admin/commande", name="admin_commande")
     */
     public function index(CommandeRepository $rep){
        $commandes = $rep->findAll();
        return $this->render('backoffice/gestionVentes/commandes/liste_cmd.html.twig',
        ['commandes'=>$commandes]
        );
     }


    /**
     * @Route("/admin/commande_supprimer/{id}", name="commande_supprimer")
     */
    public function supprimerCommande($id, ProductCartRepository $productCartRepository,CommandeRepository $rep, EntityManagerInterface $em){
        $query = $em->createQuery("DELETE FROM APP\Entity\ProductCart s where s.idCommande = :id")
            ->setParameter("id", $id);
        $query->execute();
        $commande = $rep->find(intval($id));
        $em->remove($commande);
        $em->flush();

        return $this->redirectToRoute('admin_commande');
    }

    /**
     * @param $id
     * @Route("admin/commande_show/{id}", name="commande_detail")
     */
    public function commadeDetails($id, ProductCartRepository $rep, EntityManagerInterface $em){
        $query = $em->createQuery("select s From APP\Entity\ProductCart s where s.idCommande = :id")
                    ->setParameter("id", $id);
        $commandes = $query->getResult();

        //dd($commandes);
        return $this->render('backoffice/gestionVentes/commandes/commande_detail.html.twig', [
            'commandes'=>$commandes
        ]);
    }
    /**
     * @param $id
     * @Route("/mes_commandes/commande_detail/{id}", name="cmd_detail")
     */
    public function cmdShow($id, ProductCartRepository $rep, EntityManagerInterface $em){
        $query = $em->createQuery("select s From APP\Entity\ProductCart s where s.idCommande = :id")
            ->setParameter("id", $id);
        $commandes = $query->getResult();


        return $this->render('frontoffice/commande_detail_client.html.twig', [
            'commandes'=>$commandes
        ]);
    }

    /**
     * @Route("admin/command_product/{id}", name="products_cmd_detail")
     */
    public function productCmdDetail(ProduitsRepository $rep, $id){
        $produits = $rep->find($id);
       // dd($produits);
        return $this->render('backoffice/gestionVentes/commandes/detail_product_cmd.html.twig',[
            'produit'=>$produits
        ]);
    }

    /**
     * @Route("/mes_commandes_detail_produits/{id}", name="cmd_detail_produit")
     */
    public function productCmdDetailClient(ProduitsRepository $rep, $id){
        $produit = $rep->find($id);
        // dd($produits);
        return $this->render('frontoffice/detail_product_cmd_client.html.twig',[
            'produit'=>$produit
        ]);
    }



     /**
     * @Route("/admin/commande_contenu_modifier/{id}", name="admin_cmd_modifier")
     */
    public function modifierCommande(Request $req, EntityManagerInterface $em, $id, ProductCartRepository $rep){

        $productCart = $rep->find($id);
        /*$query = $em->createQuery("select s From APP\Entity\ProductCart s where s.idCommande = :id")
            ->setParameter("id", $id);
        $commandes = $query->getResult();*/

        $form = $this->createForm(ProductCartType::class, $productCart);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            $em->flush();
            return $this->redirectToRoute('admin_commande');
        }

        return $this->render('backoffice/gestionVentes/commandes/modifier_cmd.html.twig',
            ["formProdCmd"=>$form->createView()]);
     }

     /**
      * @Route("/mes_commandes", name="mes_cmd")
      */
    public function commandeClient(CommandeRepository $rep, EntityManagerInterface $em){
        $id =1;
        $query = $em->createQuery("select s From APP\Entity\Commande s where s.idUser = :id")
            ->setParameter("id", $id);
        $commande = $query->getResult();

        return $this->render('frontoffice/mescmd.html.twig',
            ['commande'=>$commande]
        );
    }

    /**
     * @param $id
     * @param ProductCartRepository $rep
     * @Route("/admin/commande_suppr_produit/{id}", name="suppr_prod_cmd")
     */
     public function supprimerProduitCmd($id, ProductCartRepository $rep, EntityManagerInterface $em){
         $query = $em->createQuery("select s From APP\Entity\ProductCart s where s.idProduit = :id")
             ->setParameter("id", $id);
         $produit = $query->getResult();

         foreach ($produit as $product) {
             $em->remove($product);
         }
         $em->flush();

         return $this->redirectToRoute('admin_commande');
     }

    /**
     * @Route("/passer_commande", name="passer_cmd")
     */
    public function passerCommande(CartService $cartService){
        $index = 0;
        $idUser = 1;
        $totalPayment = $cartService->getTotal();
        $date = date('Y-m-d h:i:sa');
        $order = new Commande($idUser,$totalPayment,$date);
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        if($index==0){
            $em->flush();
            $index = 1;
        }
        if($index==1){
            foreach ($cartService->getFullCart() as $item){
                $idOrder = $order->getId();
                $idProduct = $item['product']->getId();
                $quantity = $item['quantity'];
                $productCart = new ProductCart($idOrder,$idProduct,$quantity);
                $em->persist($productCart);
            }
            $em->flush();
        }




        return $this->redirectToRoute("liste_produits");
    }

    /**
     * @Route("/passer_commande", name="passer_cmde")
     */
    public function passerCmd(SessionInterface $session ,ProduitsRepository $rep, Request $request, EntityManagerInterface $em)
    {
        $panier = $session->get('panier', []);
        $commandes = new Commandes();
        $form = $this->createForm(CommandesType::class, $commandes);

        $panierWithData = [];
        $idProd = 0;
        foreach ($panier as $id =>$quantity) {

            $panierWithData[]= [
                'product'=>$rep->find($id),
                'quantity'=>$quantity
            ];
        }


        $total = 0;
        $qtiteProd=0;
        $form->handleRequest($request);

        $produits[] = new Produits();
        //$produits[] = $panierWithData['product'];

        foreach($panierWithData as $item){
            //$idProd = $item['product']->getId();
            $totalItem = $item['product']->getPrix() * $item['quantity'];
            $total += $totalItem;
            $qtiteProd = $item['quantity'];
        }

        if($form->isSubmitted() && $form->isValid()){
            $commandes->setQtiteProd($qtiteProd);
            $commandes->addProduit($item['product']);
            $em->persist($commandes);
            $em->flush();
            return $this->redirectToRoute('mes_cmd');
        }

        return $this->render('frontoffice/commander.html.twig', [
            'items'=>$panierWithData,
            'total'=>$total,
            'formCommande'=>$form->createView()
        ]);
    }


}
