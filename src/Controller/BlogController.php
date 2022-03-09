<?php

namespace App\Controller;
use App\Repository\CalendarRepository;
use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Repository\CmntRepository;
use App\Entity\Cmnt;
use App\Form\CmntType;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;
use Knp\Component\Pager\PaginatorInterface;
use Gregwar\CaptchaBundle\Type\CaptchaType;
class BlogController extends AbstractController
{
    /**
     * @Route("/clnd", name="main")
     */
    public function index(CalendarRepository $calendar)
    {
        $events = $calendar->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('main/index.html.twig', compact('data'));
    }




    /**
     * @param BlogRepository $repository
     * @return Response
     * @Route("/blog", name="blog")
     */
    public function indexx(BlogRepository $repository,PaginatorInterface $paginator,\Symfony\Component\HttpFoundation\Request $request)
    {
        $listeblog=$repository->findAll();
        $articles = $paginator->paginate(
            $listeblog, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('frontoffice/blog.html.twig', [
            'articles' => $articles,
        ]);




        //return $this->render('frontoffice/blog.html.twig', ['listeblog'=>$listeblog

       // ]);
    }
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @param BlogRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/blogf/{id}",name="blogf")
     */
    public function blogf($id,BlogRepository $repository,\Symfony\Component\HttpFoundation\Request $request){
        $blogf=$repository->findAll();
        $blog=$repository->findOneBy(['id'=>$id]);
        $cmnt = new Cmnt();
        $formc = $this->createForm(CmntType::class, $cmnt);
        $formc->add('captcha', CaptchaType::class);
        $formc->add('Ajouter',SubmitType::class);
        $formc->handleRequest($request);
        if ($formc->isSubmitted() && $formc->isValid()) {
            $cmnt->setCreatedAt(new \DateTimeImmutable());
            $cmnt->setBlog($blog);
            $em = $this->getDoctrine()->getManager();
            $em->persist($cmnt);
            $em->flush();
            $this->addFlash('message','votre commentaire a bien été envoyé');
            return $this->redirectToRoute('blogf',['id'=>$blog->getId()]);
        }

        return $this->render('frontoffice/blogf.html.twig', ['blogf'=>$blogf,'id'=>$id,'formc'=>$formc->createView(),'blog'=>$blog

        ]);

    }
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @param BlogRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/pdf/{id}",name="pdf")
*/
    public function pdf(\Symfony\Component\HttpFoundation\Request $request,\Knp\Snappy\Pdf $snappy,$id,BlogRepository $repository){
       // $this->knpSnappy = $knpSnappy;
        //$snappy = $this->get("knp_snappy.pdf");
        $blogf=$repository->findAll();

                //$html ="{{c.titre}}"



     $html=$this->renderView('frontoffice/pdf.html.twig',["title"=>"mmmmmmmmmmmmm",'blogf'=>$blogf,'id'=>$id]);
     $filename = "pdf";

         return new Response($snappy->getOutputFromHtml($html),200,
             ['Content-Type'=>'application/pdf','Content-Disposition'=> 'inline;filename="'.$filename.'.pdf"' ]

         );

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
    public function addblog(\Symfony\Component\HttpFoundation\Request $request,KernelInterface $kernel){
        $blog=new Blog();
        $form=$this->createForm(BlogType::class,$blog);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $file = $blog->getPhoto();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
          try {
             // $imagesDir = $kernel->getProjectDir().'/public/uploads/images'; // equivalent à $this->getParameter('images_directory')
              //dump($imagesDir) ;
              $file->move($kernel->getProjectDir().'/public/uploads/images',
                    $fileName);
          } catch (FileException $e) {
                // ... handle exception if something happens during file upload

           }

            //$file->move($this->getParameter('images_directory'),$fileName);

            $em = $this->getDoctrine()->getManager();
            $blog->setPhoto($fileName);
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
    public function updatebg(\Symfony\Component\HttpFoundation\Request $request,$id,BlogRepository $repository,KernelInterface $kernel){
        $blog=$repository->find($id);
        $form=$this->createForm(BlogType::class,$blog);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $file = $blog->getPhoto();
           $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move($kernel->getProjectDir().'/public/uploads/images',
                    $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $em = $this->getDoctrine()->getManager();
            $blog->setPhoto($fileName);
            $em->persist($blog);
            $em->flush();
            return $this->redirectToRoute('listeblog');
        }
        return $this->render('backoffice/updateblog.html.twig',['f'=>$form->createView()]);
    }













}
