<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function AddCa(Request $request1):Response
    {
        $cat=new Categorie();
         $form = $this->createForm(CategorieType::class ,$cat);
        $form->handleRequest($request1);
        if($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();
            return $this->redirectToRoute('categorie');
        }
        return $this->render('categorie/index.html.twig', [
            'f' =>$form->createView(),
        ]);
    }

}
