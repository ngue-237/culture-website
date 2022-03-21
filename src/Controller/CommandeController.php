<?php

namespace App\Controller;


use Stripe\Stripe;
use App\Entity\Commande;
use App\Entity\Produits;
use App\Entity\Commandes;
use App\Entity\ProductCart;
use App\Form\CommandesType;
use App\Form\ProductCartType;
use App\Service\Cart\CartService;
use Doctrine\ORM\Query\Expr\Math;
use App\Repository\CommandeRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductCartRepository;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;
use Knp\Component\Pager\PaginatorInterface;

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
     * @Route("/passer_commande", name="passer_cmd")
     */
    // public function passerCommande(CartService $cartService, Request $req){
        
    //     $index = 0;
    //     $idUser = 1;

    //     $totalPayment = $cartService->getTotal();
    //     $date = date('Y-m-d h:i:sa');
    //     $order = new Commande($idUser,$totalPayment,$date);
    //     $em = $this->getDoctrine()->getManager();
    //     $em->persist($order);
    //     if($index==0){
    //         $em->flush();
    //         $index = 1;
    //     }
    //     if($index==1){
    //         foreach ($cartService->getFullCart() as $item){

    //             $idOrder = $order->getId();
    //             $idProduct = $item['product']->getId();
    //             $quantity = $item['quantity'];
    //             $productCart = new ProductCart($idOrder,$idProduct,$quantity);
    //             $em->persist($productCart);
    //         }
    //         $em->flush();
    //     }




    //     return $this->redirectToRoute("liste_produits");
    // }


    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout(CartService $cartService, SessionInterface $session):Response{
        $index = 0;
       
        $idUser = $this->getUser()->getId();
        

        $totalPayment = $cartService->getTotal();
        $totalConvert = round($totalPayment / 3,12);
        //dd($totalConvert);
        $date = date('Y-m-d h:i:sa');
        $statuts = true;
        $order = new Commande($idUser,$totalPayment,$date,$statuts);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        if($index==0){
            $em->flush();
            $index = 1;
        }
        $lineItems =[];
        
        foreach ($cartService->getFullCart() as $item){
            $lineItems[] = [
                'price_data'=>[
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['product']->getNom(),
                    ],
                    'unit_amount'=> ceil(($item['product']->getPrix())/3)*100,
                ],
                'quantity' => $item['quantity'],
            ];
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
        
        \Stripe\Stripe::setApiKey('sk_test_51KXz4dDDjhiT3y1zNrDHTIzAl7iggyUDIXJZlgjpNYWfXSmg5apwi8bipqhMinFXle35pNV7uYsDpfTpiBTlkHVG00IKuSacB7');
        $sessionS = \Stripe\Checkout\Session::create([
            'payment_method_types'=>['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        
       
       $panier = $session->get('panier',[]);
       // dd($panier);
        
        $session->set('panier', []);
       
        
        return $this->redirect($sessionS->url, 303);
    }
    /**
     * @Route("/success_url", name="success_url")
     */
    public function successUrl(FlashyNotifier $flashy,  SessionInterface $session){

       $flashy->success('Votre commande a été ajouter!');
    //    $panier = $session->get('panier', []);
    //      dd($panier);
        return $this->redirectToRoute('mes_cmd');
    }
    /**
     * @Route("/cancel_url", name="cancel_url")
     */
    public function cancelUrl(){
        return $this->render('frontoffice/cancel.html.twig');
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
    public function commadeDetails($id,  EntityManagerInterface $em,ProductCartRepository $rep ){
        // $idU = $session;
        // dd($session);
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
    public function cmdShow($id, EntityManagerInterface $em, SessionInterface $session, ProductCartRepository $productCartRepository){
        $idU = $session;
        //dd($session);
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
    public function productCmdDetailClient(ProduitsRepository $rep, $id, ProductCartRepository $rep1){
        $produit = $rep->find($id);
        // dd($produits);
        return $this->render('frontoffice/detail_product_cmd_client.html.twig',[
            'produit'=>$produit
        ]);
    }



     /**
     * @Route("/admin/commande_contenu_modifier/{id}/{idCmd}", name="admin_cmd_modifier")
     */
    public function modifierCommande(Request $req, EntityManagerInterface $em, $id, ProduitsRepository $repProd, ProductCartRepository $rep, $idCmd, FlashyNotifier $flashy){

        $productCart = $rep->find($id);


        $form = $this->createForm(ProductCartType::class, $productCart);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            $em->flush();
            $query = $em->createQuery("select s From App\Entity\ProductCart s where s.idCommande = :id")
                ->setParameter("id", $idCmd);
            $commandes = $query->getResult();
            $total = 0;
            for($i=0; $i<count($commandes); $i++){
                $produit = $repProd->find($commandes[$i]->getIdProduit());
                $total += $produit->getPrix() * $commandes[$i]->getQuantite();
            }
            $query = $em->createQuery("update App\Entity\Commande s set s.totalPaiment = :total where s.id = :idCmd")
                ->setParameter("idCmd", $idCmd)
                ->setParameter("total", $total);
            $query->execute();
            $flashy->success('Modifier!');
            return $this->redirectToRoute('admin_commande');
        }


        return $this->render('backoffice/gestionVentes/commandes/modifier_cmd.html.twig',
            ["formProdCmd"=>$form->createView()]);
     }
     
     /**
      * @Route("/commandes/mes_commandes", name="mes_cmd")
      */
        
    public function commandeClient(CommandeRepository $rep, EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, ProductCartRepository $rep1){
        
        $id = $this->getUser()->getId();
        $query = $em->createQuery("select s From APP\Entity\Commande s where s.idUser = :id")
            ->setParameter("id", $id);
        $query = $query->getResult();

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), /*page number*/
            1 /*limit per page*/
        );

        return $this->render('frontoffice/mescmd.html.twig',
            ['commande'=>$pagination]
        );
    }

    /**
     * @param $id
     * @param ProductCartRepository $rep
     * @Route("/admin/commande_suppr_produit/{id}", name="suppr_prod_cmd")
     */
     public function supprimerProduitCmd($id, ProductCartRepository $productCartRepository, EntityManagerInterface $em){
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
     * @Route("/passer_commande", name="passer_cmde")
     */
    // public function passerCmd(SessionInterface $session ,ProduitsRepository $rep, Request $request, EntityManagerInterface $em)
    // {
    //     $panier = $session->get('panier', []);
    //     $commandes = new Commandes();
    //     $form = $this->createForm(CommandesType::class, $commandes);

    //     $panierWithData = [];
    //     $idProd = 0;
    //     foreach ($panier as $id =>$quantity) {

    //         $panierWithData[]= [
    //             'product'=>$rep->find($id),
    //             'quantity'=>$quantity
    //         ];
    //     }


    //     $total = 0;
    //     $qtiteProd=0;
    //     $form->handleRequest($request);

    //     $produits[] = new Produits();
    //     //$produits[] = $panierWithData['product'];

    //     foreach($panierWithData as $item){
    //         //$idProd = $item['product']->getId();
    //         $totalItem = $item['product']->getPrix() * $item['quantity'];
    //         $total += $totalItem;
    //         $qtiteProd = $item['quantity'];
    //     }

    //     if($form->isSubmitted() && $form->isValid()){
    //         $commandes->setQtiteProd($qtiteProd);
    //         $commandes->addProduit($item['product']);
    //         $em->persist($commandes);
    //         $em->flush();
    //         return $this->redirectToRoute('mes_cmd');
    //     }

    //     return $this->render('frontoffice/commander.html.twig', [
    //         'items'=>$panierWithData,
    //         'total'=>$total,
    //         'formCommande'=>$form->createView()
    //     ]);
    // }


}
