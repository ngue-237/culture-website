<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\CategorieType;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_prd")
     */
    public function admin_prd(): Response
    {
        return $this->render('backoffice/dashboard.html.twig');
    }

    /**
     * @Route("/admin/dashboard/admin_cat", name="admin_cat")
     */
    public function admin_cat(CategorieRepository $repository): Response
    {
        $data = $repository->findAll();
        return $this->render('backoffice/dashboardCat.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @Route("/admin/dashboard/admin_pr", name="admin_pr")
     */
    public function admin_pr(ProduitRepository $repository): Response
    {
        $data = $repository->findAll();
        return $this->render('backoffice/dashboardPr.html.twig', [
            'data' => $data,
        ]);
    }


    /**
     * @Route("/suppC/{id}", name="suppC")
     */
    public function suppC($id): Response
    {
        $cat = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $en = $this->getDoctrine()->getManager();
        $en->remove($cat);
        $en->flush();
        return $this->redirectToRoute('admin_cat');
    }

    /**
     * @Route("/suppP/{id}", name="suppP")
     */
    public function suppP($id): Response
    {
        $pr = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $en = $this->getDoctrine()->getManager();
        $en->remove($pr);
        $en->flush();
        return $this->redirectToRoute('admin_pr');
    }

    /**
     * @Route("/modifC/{id}", name="modifC")
     */
    public function modifC(Request $request, $id): Response
    {

        $cat = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $form = $this->createForm(CategorieType::class, $cat);
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
     * @Route("/modifP/{id}", name="modifP")
     */
    public function modifP(Request $request, $id): Response
    {
        $pr = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $form = $this->createForm(ProduitType::class, $pr);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('produit');
        }
        return $this->render('produit/index.html.twig', [
            'f' => $form->createView(),
        ]);
    }





}
