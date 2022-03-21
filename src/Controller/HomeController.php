<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Repository\ProduitsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitsRepository $rep, PaginatorInterface $paginator, Request $req): Response
    {   
      $produits = $rep->findAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        //dd($produits);
         $pagination = $paginator->paginate(
            $produits, 
            $req->query->getInt('page', 1), /*page number*/
            2/*limit per page*/
        );
        return $this->render('frontoffice/home.html.twig', [
            'produits' => $pagination,
        ]);
    }

    /**
     * @Route("/produits", name="liste_produits")
     */
    public function listeProduitsH(ProduitsRepository $rep, PaginatorInterface $paginator, Request $req)
    {

        $produits = $rep->findAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        //dd($produits);
         $pagination = $paginator->paginate(
            $produits, 
            $req->query->getInt('page', 1), /*page number*/
            2/*limit per page*/
        );
        return $this->render('frontoffice/home.html.twig', [
            'produits' => $pagination,
        ]);
    }
}
