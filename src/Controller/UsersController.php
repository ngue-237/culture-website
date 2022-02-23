<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Reclamation;
use App\Entity\Users;
use App\Form\ReclamationType;
use App\Form\RecupererType;
use App\Repository\ProductsRepository;
use Endroid\QrCode\Encoding\Encoding;

use App\Entity\Subs;
use App\Form\SubsType;
use App\Repository\SubsRepository;
use App\Services\QrcodeService;
use Endroid\QrCode\Builder\BuilderInterface;
use mysql_xdevapi\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/users')]
/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
   // #[Route('/', name: 'users_index', methods: ['GET'] )]
    /**
     * @param UsersRepository $usersRepository
     * @return Response
     * @Route ("/",name="users_index",methods={"GET"})
     */
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }


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
    //#[Route('/new', name: 'users_new', methods: ['GET', 'POST'])]*/
    /**
     * @Route("/new",name="users_new",methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */

    public function new(Request $request, QrcodeService $qrcodeService): Response
    {
        $user = new Users();
        $qrCode = null;
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $file = $form->get('photo')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            $user=$form->getData();
            $user->setPhoto($fileName);
            $this->addFlash('info','a new user has been created');
            $qrCode = $qrcodeService->qrcode(5555);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users_index');
        }
        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);


    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request,UsersRepository $userlogindateRepo,SessionInterface $session): Response
    {


        //verify if form is submitted with POST methode

        if ( $request->isMethod('POST')){
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = $userlogindateRepo->log_in_test($email,$password);
            $User = $this->getDoctrine()->getManager()->getRepository(Users::class)->findOneBy(['email'=>$email]);
            $session->set('User',$User);

            return $this->redirectToRoute('Usershow');
        }
        else{
            return $this->render('users/test.html.twig', [
                'session'=>$session,
            ]);
        }
    }
    /**
     * @Route("/getPasswordByEmail", name="getPasswordByEmail")
     */

    public function getPassswordByEmail(Request $request,\Swift_Mailer $mailer,SessionInterface $session) {


        $user = new Users();
        $qrCode = null;
        $form = $this->createForm(RecupererType::class, $user);
        $form->handleRequest($request);
        $email =$user->getEmail();
       //  = $form->get('email');
        $User = $this->getDoctrine()->getManager()->getRepository(Users::class)->findOneBy(['email'=>$email]);
        if($User) {
            $password = $User->getPassword();
            $message = (new \Swift_Message('Password '))
                ->setFrom('mohamed.mezghani@esprit.tn')
                ->setContentType("text/html")
                ->setTo($email)
                ->setBody("<strong style='color: red;'> your password is  </strong> <span style='color:red;'>".$password."</span> ");
            $mailer->send($message) ;
            return $this->redirectToRoute('login');

        }
        return $this->render('users/resetpassword.html.twig', [
            'form' => $form->createView(),
            'session'=>$session,
        ]);

    }


    /**
     * @Route("/find/{email}/{password}", name="find")
     */
    public function finduserbyEmailandpassword($email,$password,SerializerInterface $serializer)
    {
        $em= $this->getDoctrine()->getManager();
        $query = $em->createQuery('select u.id as ID  from App\Entity\Users u 
         where   u.email=:_mail 
        and
        u.password=:_pass ');

        $query->setParameter(":_mail", $email);
        $query->setParameter(":_pass", $password);
        $result = $query->getResult();
        $id=-1;
        foreach ($result as $r)
        {

            $id=$r['ID'];
            break;

        }
        $formatted = $serializer->normalize($id);
        return new JsonResponse($formatted);
    }
    /**
     * @Route ("/DeleteJson/{id}", name="DeleteJson")
     */
    public function DeleteGroupeJson($id)
    {
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository(Users::class)->find($id);
        $em->remove($repo);
        $em->flush();
        return new Response('User Deleted');
    }
    /**
     * @Route("/qr", name="qr")
     */
    public function qrcode($query)
    {

        $url = 'https://www.google.com/search?q=';

        $objDateTime = new \DateTime('NOW');
        $dateString = $objDateTime->format('d-m-Y H:i:s');




        $result = $this->bulider
            ->data($url.$query)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->labelText($dateString)
            ->build()
        ;

        //generate name
        $namePng = uniqid('', '') . '.png';

        //Save img png
        $result->saveToFile((\dirname(__DIR__,2).'/public/assets/qr-code/'.$namePng));

        return $result->getDataUri();
    }
    /**
     * @Route("/updatetomobile/{id}/{name}/{lastname}/{email}/{password}/{gender}/{photo}/{phone}", name="updmobile")
     */
    public function updateJson(Request $request,$id,$name,$lastname,$email,$password,$gender,$photo,$phone, QrcodeService $qrcodeService)
    {
        $qrCode = null;
        $content=$request->getContent();
        $em=$this->getDoctrine()->getManager();
        $repository=$this->getDoctrine()->getRepository(Users::class);
        $Users=$repository->find($id);
        $em->remove($Users);
        //  $Products->setProductId($id);
        $Users->setName($name);
        $Users->setLastname($lastname);
        $Users->setEmail($email);
        $Users->setPassword($password);
        $Users->setGender($gender);
        $Users->setPhoto($photo);
        $Users->setPhone($phone);
        $qrCode = $qrcodeService->qrcode($Users->getId());
        $em->persist($Users);
        $em->flush();
        return new Response('User Modifier');
    }
    /**
     * @Route("/addusermobile/{name}/{lastname}/{email}/{password}/{gender}/{photo}/{phone}", name="addmobile")
     */
    public function addusertomobile($name,$lastname,$email,$password,$gender,$photo,$phone , QrcodeService $qrcodeService)
    {
        /*u.getName()+"/"+u.getLastname()
        +"/"+u.getEmail()+"/"+u.getPassword()+"/"
        +u.getGender()+"/"+u.getPhoto()+"/"
        +u.getPhone()+"/"+u.getBirthday();*/
        $qrCode = null;
        $Users = new Users();
        $Users->setName($name);
        $Users->setLastname($lastname);
        $Users->setEmail($email);
        $Users->setPassword($password);
        $Users->setGender($gender);
        $Users->setPhoto($photo);
        $Users->setPhone($phone);
       // $Users->setBirthday($birthday);
        $em=$this->getDoctrine()->getManager();
        $qrCode = $qrcodeService->qrcode($Users->getPhone());
        $em->persist($Users);
        $em->flush();
        return new Response('Groupe added');

    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout(SessionInterface $session){
        $session->remove('User');

        return $this->redirectToRoute('login', [
            'session'=>$session,
        ]);
    }

    //#[Route('/newF', name: 'users_newF', methods: ['GET', 'POST'])]
    /**
     * @Route("/newF", name="users_newF", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function newF(Request $request,SessionInterface $session): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $file = $form->get('photo')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            $user=$form->getData();
            $user->setPhoto($fileName);

            $this->addFlash('info','a new user has been created');

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('login');
        }


        return $this->render('users/newF.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'session'=>$session,
        ]);


    }



    /**
     * @Route("/Usershow", name="Usershow")
     */

    public function Usershow(SessionInterface $session)
    {
        if($session->has('User')){
            $repository=$this->getDoctrine()->getRepository(users::Class);
            $User=$repository->find($session->get('User')->getId());

            return $this->render('users/profile.html.twig', [
                'session' => $session,
                'user' => $User,
            ]);
        }
        else{
            return $this->redirectToRoute('login');
        }
    }

    /**
     *
     * @Route("/searchRec",name="searchRec")
     */
    public function searchUser(UsersRepository $repository,Request $request){
        $data=$request->get('search');
        $users=$repository->findByName($data);
        return $this->render('users/index.html.twig',['users'=>$users]);
    }
    /**
     * @param UsersRepository $repository
     * @return Response
     * @Route ("/trousers",name="trousers")
     *
     */
    public function orderByMailSQL(UsersRepository $repository,Request $request){
        $data=$request->get('tri');
        $users=$repository->OrderByMail($data);
        return $this->render('users/index.html.twig',['users'=>$users]);
    }


    //#[Route('/{id}', name: 'users_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    //#[Route('/{id}/edit', name: 'users_edit', methods: ['GET', 'POST'])]
    /**
     * @Route("/{id}/", name="users_show", methods={"GET","POST"})
     */
    public function edit2(Request $request, Users $user): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/del/{id}", name="users_delete", methods={"POST"})
     */
    public function delete(Request $request, Users $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('users_index');
    }

    /**
     *
     * @Route("/stats",name="stats")
     */
    public function statistique(UsersRepository $repository){

        $users = $this->getDoctrine()->getRepository(Users::class)->findAll();
        //$users=$repository->findAll();
        var_dump( $users);
        $name=[];
        $gender=[];
        foreach ($users as $user){
            $name[]= $user->getName();
            $gender[]=$user->getGender();

        }
        return $this->render('users/stats.html.twig',
            ['name'=> json_encode($name),
                'gender'=>json_encode($gender)]);

    }




}
