<?php

namespace App\Service\Cart;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productsRepository;

    public function __construct(SessionInterface $session, ProduitsRepository $productsRepository){

        $this->session = $session;
        $this->productsRepository = $productsRepository;
    }

    public function add(int $id){

        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]=1;
        }

        $this->session->set('panier',$panier);
    }
    public function decrease(int $id){

        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            if($panier[$id]>1){
                $panier[$id]--;
            }
            else{
                unset($panier[$id]);
            }
        }
        $this->session->set('panier',$panier);
    }

    public function remove(int $id){
        $panier = $this->session->get('panier',[]);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }

    public function getFullCart() :array {
        $panier = $this->session->get('panier',[]);

        $panierWithData = [];

        foreach($panier as $id => $quantity){
            $panierWithData[]=[
                'product' => $this->productsRepository->find($id),
                'quantity'=> $quantity
            ];
        }

        return $panierWithData;
    }

    public function getTotal() :float {
        $total = 0;

        $panierWithData = $this->getFullCart();
        foreach($panierWithData as $item){

            $price = $item['product']->getPrix();
            $totalItem = $price * $item['quantity'];
            $total += $totalItem;

        }
        return $total;
    }


}