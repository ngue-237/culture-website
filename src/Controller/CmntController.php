<?php

namespace App\Controller;

use App\Repository\CmntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CmntController extends AbstractController
{
    /**
     * @Route("/cmnt", name="cmnt")
     */
    public function index(): Response
    {
        return $this->render('cmnt/index.html.twig', [
            'controller_name' => 'CmntController',
        ]);
    }
    /**
     * @param CmntRepository $repository
     * @return Response
     * @Route("/listecmnt",name="listecmnt")
     */
    public function listecmnt(CmntRepository  $repository){
        $listecmnt=$repository->findAll();
        return $this->render( 'backoffice/listecmnt.html.twig',['listecmnt'=>$listecmnt]);
    }

    /**
     * @Route("/deletecmnt/{id}",name="dcmnt")
     */
    public function delete($id,CmntRepository $repository){
        $cmnt=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($cmnt);
        $em->flush();
        return $this->redirectToRoute('listecmnt');

    }

}
