<?php

namespace App\Controller;
use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CmntRepository;
use App\Entity\Cmnt;
use App\Form\CmntType;
use http\Env\Request;
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
    public function listecmnt(CmntRepository $repository)
    {
        $listecmnt = $repository->findAll();
        return $this->render('backoffice/listecmnt.html.twig', ['listecmnt' => $listecmnt]);
    }
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @param BlogRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/updatecmnt/{id}",name="updatecmnt")
     */
    public function updatecmnt(\Symfony\Component\HttpFoundation\Request $request,$id,CmntRepository $repository,BlogRepository $repos){
        $bcmnt=$repository->find($id);
        $bb=$repository->findOneBy(['id'=>$id]);

       $idb=$bb->getBlog();
        $blog=$repos->findOneBy(['id'=>$idb]);
        $formbc=$this->createForm(CmntType::class,$bcmnt);
        $formbc->handleRequest($request);
        if ($formbc->isSubmitted()&& $formbc->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bcmnt);
            $em->flush();
            return $this->redirectToRoute('blogf',['id'=>$blog->getId()]);
        }
        return $this->render('frontoffice/updatecmnt.html.twig',['fbc'=>$formbc->createView()]);
    }
    /**
     * @Route("/aaa/{id}",name="aaa")
     */
    public function deletec($id, CmntRepository $repository,BlogRepository $repos)
    {
        $bb=$repository->findOneBy(['id'=>$id]);
        $idb=$bb->getBlog();
        $blog=$repos->findOneBy(['id'=>$idb]);
        $cmnt = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($cmnt);
        $em->flush();
        return $this->redirectToRoute('blogf',['id'=>$blog->getId()]);

    }


    /**
     * @Route("/deletecmnt/{id}",name="dcmnt")
     */
    public function delete($id, CmntRepository $repository)
    {
        $cmnt = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($cmnt);
        $em->flush();
        return $this->redirectToRoute('listecmnt');

    }
}









