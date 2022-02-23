<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(): Response
    {
        return $this->render('frontoffice/cart.html.twig', [
            
        ]);
    }

    /**
     *@Route("/admin/cart", name="admin_cart")
     *
     * @return void
     */
    public function cartAdmin(){

        return $this->render('backoffice/cart.html.twig');
    }
}
