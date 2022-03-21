<?php

namespace App\Controller;

use App\Entity\Cathegorie;
use App\Form\CathegorieType;
use App\Repository\CathegorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CathegorieController extends AbstractController
{
    /**
     * @Route("/admin/dashboard/admin_cat", name="admin_cat")
     */
    public function admin_cat(CathegorieRepository $repository): Response
    {
        $data = $repository->findAll();
        return $this->render('backoffice/dashboardCat.html.twig', [
            'data' => $data,
        ]);
    }
    /**
     * @Route("/categorie", name="categorie")
     */
    public function AddCa(Request $request1):Response
    {
        $cat=new Cathegorie();
        $form = $this->createForm(CathegorieType::class ,$cat);
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
    /**
     * @Route("/suppC/{id}", name="suppC")
     */
    public function suppC($id): Response
    {
        $cat = $this->getDoctrine()->getRepository(Cathegorie::class)->find($id);
        $en = $this->getDoctrine()->getManager();
        $en->remove($cat);
        $en->flush();
        return $this->redirectToRoute('admin_cat');
    }
    /**
     * @Route("/modifC/{id}", name="modifC")
     */
    public function modifC(Request $request, $id): Response
    {
        $cat = $this->getDoctrine()->getRepository(Cathegorie::class)->find($id);
        $form = $this->createForm(CathegorieType::class, $cat);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('categorie');
        }
        return $this->render('categorie/index.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    /**
     * @Route("/searchCategorie", name="categorie_search")
     */
    public function searchCategorie(Request $request1)
    {
        $data=   $request1->get('categorie');
        $em=$this->getDoctrine()->getManager();
        if($data == ""){
            $data=$em->getRepository(Cathegorie::class)->findAll();
        }else{
            $data=$em->getRepository(Cathegorie::class)->findBy(
                ['designation'=> $data]
            );
        }

        return $this->render('backoffice/dashboardCat.html.twig', array(
            'data' => $data
        ));

    }
}
