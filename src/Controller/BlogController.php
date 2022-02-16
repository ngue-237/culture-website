<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(): Response
    {
        return $this->render('frontoffice/inblogdex.html.twig', [
            
        ]);
    }

    /**
     * @param BlogRepository $repository
     * @return Response
     * @Route("/listeblog",name="listeblog")
     */
    public function listeblog(BlogRepository $repository){
        $listeblog=$repository->findAll();
        return $this->render( 'backoffice/listeblog.html.twig',['listeblog'=>$listeblog]);
    }

    /**
     * @Route("/delete/{id}",name="d")
     */
    public function delete($id,BlogRepository $repository){
        $blog=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();
        return $this->redirectToRoute('listeblog');

    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/addblog",name="add")
     */
    public function addblog(\Symfony\Component\HttpFoundation\Request $request){
        $blog=new Blog();
        $form=$this->createForm(BlogType::class,$blog);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
            return $this->redirectToRoute('listeblog');
        }
        return $this->render('backoffice/addblog.html.twig',['form'=>$form->createView()]);
        }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @param BlogRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/updateblog/{id}",name="updateblog")
     */
    public function updatebg(\Symfony\Component\HttpFoundation\Request $request,$id,BlogRepository $repository){
        $blog=$repository->find($id);
        $form=$this->createForm(BlogType::class,$blog);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
            return $this->redirectToRoute('listeblog');
        }
        return $this->render('backoffice/updateblog.html.twig',['f'=>$form->createView()]);
    }
}
