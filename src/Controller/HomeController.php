<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $repository,CategorieRepository $repository1): Response
    {
        $data = $repository->findAll();
        $data1 = $repository1->findAll();
        return $this->render('frontoffice/home.html.twig', [
            'data' => $data,
            'data1' => $data1,
        ]);
    }






}