<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/produits", name="liste_produits")
     */
    public function listeProduits(ProduitsRepository $rep, PaginatorInterface $paginator, Request $req)
    {

        $produits = $rep->findAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        //dd($produits);
         $pagination = $paginator->paginate(
            $produits, 
            $req->query->getInt('page', 1), /*page number*/
            2/*limit per page*/
        );
        return $this->render('frontoffice/produits.twig', [
            'produits' => $pagination,
        ]);
    }

    

     /**
     * @Route("/admin/dashboard/admin_pr", name="admin_pr")
     */
    public function admin_pr(ProduitsRepository $repository): Response
    {
        $data = $repository->findAll();
        return $this->render('backoffice/dashboardPr.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @Route("/searchProduit", name="produit_search")
     */
    public function searchProduit(Request $request)
    {
        $data=   $request->get('produit');
        $em=$this->getDoctrine()->getManager();
        if($data == ""){
            $data=$em->getRepository(Produit::class)->findAll();
        }else{
            $data=$em->getRepository(Produit::class)->findBy(
                ['designation'=> $data],
            );
        }

        return $this->render('backoffice/dashboardPr.html.twig', array(
            'data' => $data
        ));

    }
    /**
     * @Route("/suppP/{id}", name="suppP")
     */
    public function suppP($id): Response
    {
        $pr = $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $en = $this->getDoctrine()->getManager();
        $en->remove($pr);
        $en->flush();
        return $this->redirectToRoute('admin_pr');
    }

    /**
     * @Route("/modifP/{id}", name="modifP")
     */
    public function modifP(\Symfony\Component\HttpFoundation\Request $request, $id): Response
    {
        $pr = $this->getDoctrine()->getRepository(Produits::class)->find($id);
        $form = $this->createForm(ProduitsType::class, $pr);
        
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form->get('img')->getData();
                
                $fileName= md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                        $this->getParameter('images_directory1'),
                        $fileName
                    );
            
                
                $em = $this->getDoctrine()->getManager();
                $pr->setImg($fileName);
                $em->flush();
            return $this->redirectToRoute('admin_pr');
        }
        return $this->render('produit/index.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit", name="produit")
     */
    public function AddPr(\Symfony\Component\HttpFoundation\Request $request1): Response
        {
            $pr = new Produits();
            $form = $this->createForm(ProduitsType::class, $pr);
            $form->handleRequest($request1);
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('img')->getData();
                
                $fileName= md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                        $this->getParameter('images_directory1'),
                        $fileName
                    );
            
                
                $em = $this->getDoctrine()->getManager();
                $pr->setImg($fileName);
                $em->persist($pr);
                $em->flush();
                return $this->redirectToRoute('admin_pr');
            }
            return $this->render('produit/index.html.twig', [
                'f' => $form->createView(),
            ]);
        }
}
