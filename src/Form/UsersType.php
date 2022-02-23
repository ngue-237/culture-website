<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    /*
Cockamouse 🪳 🐁, date d’envoi : Aujourd’hui, à 00:20
public function ajouterUser(Request $req){

        $user= new Users();
        $form= $this->createFormBuilder($user)
            ->add('User_name',TextType::class)
            ->add('User_lastname',TextType::class)
            ->add('User_email',TextType::class)
            ->add('User_phone',TextType::class)
            ->add('User_password',PasswordType::class)
            ->add('User_photo',FileType::class)
            ->add('User_gender',ChoiceType::class,['choices'=>[  'Female'=>'female', 'Male'=>'male']])
            ->add('Ajouter',SubmitType::class)
            ->getForm();
        $form->handleRequest($req);
        if( $form->isSubmitted() && $form->isValid()){
            $user->setUserRole(0);
            $file=$user->getUserPhoto();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            $user=$form->getData();
            $user->setUserPhoto($fileName);
            try{
                $file->move(
                    $this->getParameter('UserImage_directory'),$fileName
                );
            }
            catch(FileNotFoundException $e){}
            $em= $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('users_list');
        }
        return $this->render('users/ajouter.html.twig',['form' => $form->createView()]);

    }*/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('email')
            ->add('password',PasswordType::class)
            ->add('gender', ChoiceType::class,['choices'=>[  'Female'=>'female', 'Male'=>'male']])
            ->add('photo',FileType::class, array('data_class' => null))
            ->add('phone')
            ->add('birthday')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
