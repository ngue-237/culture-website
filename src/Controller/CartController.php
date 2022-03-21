<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Cart\CartService;

class CartController extends AbstractController
{
    /**
     * @Route("/panier/cart", name="cart")
     */
    public function index(SessionInterface $session, ProduitsRepository $rep,CartService $cartService)
    {
        /*$panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id =>$quantity) {
            $panierWithData[]= [
                'product'=>$rep->find($id),
                'quantity'=>$quantity
            ];
        }

        $total = 0;
        foreach($panierWithData as $item){
            $totalItem = $item['product']->getPrix() * $item['quantity'];
            $total += $totalItem;
        }
    */

        return $this->render('frontoffice/cart.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }



    /**
     * @Route("/panier/supprimer/{id}", name="cart_supprimer")
     */
    public function supprimer($id, CartService $cartService){
        $cartService->remove($id);
        return $this->redirectToRoute("cart");
    }

    /**
     *@Route("/admin/cart", name="admin_cart")
     *
     * @return void
     */
    public function cartAdmin(){

        return $this->render('backoffice/cart.html.twig');
    }

    /**
     * @param $id
     * @Route("/panier/ajouter_au_panier/{id}", name="cart_add")
     */
    public function ajouterPanier($id, CartService $cartService){
        $cartService->add($id);
        return $this->redirectToRoute("cart");
    }

    /**
     * @param $id
     * @Route("/panier/retirer_au_panier/{id}", name="cart_remove")
     */
    public function retirerPanier($id,CartService $cartService){
        $cartService->decrease($id);
        return $this->redirectToRoute("cart");
    }
}
