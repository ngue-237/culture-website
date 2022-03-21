<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/admin/users", name="users_lists")
     */
    public function index(UsersRepository $rep): Response
    {
        $users = $rep->findAll();
        return $this->render('backoffice/gestionUsers/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/users_delete/{id}", name="users_delete")
     */
    public function usersDelete(UsersRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $user = $rep->find($id);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('users_lists');
    }

    /**
     * Undocumented function
     *
     * @Route("/admin/users_edit/{id}", name="users_edit")
     */
    public function usersEdit ($id,UsersRepository $rep, Request $req, EntityManagerInterface $em){
       $user  = $rep->find($id);
        $form = $this->createForm(UserType::class, $user);
         
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            //dd('hello');
            $em->flush();
            return $this->redirectToRoute('users_lists');
        }

        return $this->render('backoffice/gestionUsers/edit.html.twig', [
            'form'=>$form->createView()
        ]); 
    }

    /**
     * @Route("/register", name="user_register")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function register(EntityManagerInterface $em, Request $req, UserPasswordEncoderInterface $encoder): Response{
        $user  = new Users();
        $form = $this->createForm(UserType::class, $user);
        $form->add('password')
            ->add('passwordConfirm');
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles(['ROLE_USER']);
            
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('security_login');
        }

        return $this->render('frontoffice/register.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    /** @Route("/admin/register", name="user_admin_register")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function registerAdmin(EntityManagerInterface $em, Request $req, UserPasswordEncoderInterface $encoder): Response{
        $user  = new Users();
        $form = $this->createForm(UserType::class, $user);
        $form->add('password', PasswordType::class,[
            
        ])
            ->add('passwordConfirm', PasswordType::class,[
            
        ]);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles(['ADMIN_USER']);
            
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('users_lists');
        }

        return $this->render('backoffice/gestionUsers/register.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
      * @return Response
     * @Route ("/login", name="security_login")
     */
    public function login(Request $req, UsersRepository $rep):Response{
        
        
        
        return $this->render('frontoffice/login.html.twig');
    }
    
    /**
     * @Route("/logout", name="security_logout")
     */
    public function loggout(){

    }
}
