<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function AddPr(\Symfony\Component\HttpFoundation\Request $request1): Response
        {
            $pr = new Produit();
            $form = $this->createForm(ProduitType::class, $pr);
            $form->handleRequest($request1);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($pr);
                $em->flush();
                return $this->redirectToRoute('Produit');
            }
            return $this->render('produit/index.html.twig', [
                'f' => $form->createView(),
            ]);
        }
    }

