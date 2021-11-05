<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Contact;
use App\Entity\Immobilier;
use App\Entity\Image;
use App\Entity\ImmobilierSearch;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\ImmobilierContactType;
use App\Form\ImmobilierSearchType;
use App\Form\ImmobilierType;
use App\Form\SearchAnnonceType;
use App\Form\SearchForm;
use App\Notification\ContactNotification;
use App\Repository\ImmobilierRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/*use Symfony\Component\DomCrawler\Image;*/

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/immobilier")
 */
class ImmobilierController extends AbstractController
{
    /**
     * @Route("/", name="immobilier_index", methods={"GET"})
     */
    public function index(ImmobilierRepository $immobilierRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $search=new ImmobilierSearch();
        $form=$this->createForm(ImmobilierSearch::class,$search);
        $form->handleRequest($request);


        $pagination=$paginator->paginate(
            $immobilierRepository->findAll(),
            $request->query->getInt('page',1),4
        );



        return $this->render('immobilier/index.html.twig', [
            'pagination' => $pagination,
            'form'=>$form->createView()




        ]);

    }

    /**
     * 
     * @Route("/new", name="immobilier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $immobilier = new Immobilier();
        $form = $this->createForm(ImmobilierType::class, $immobilier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on recupère les images
            $images=$form->get('image')->getData();

            //on boucle sur les images
            foreach ($images as $image){
                //on genere un nouveau nom de fichier
                $file=md5(uniqid()) . '.' .$image->guessExtension();

                $image->move(
                  $this->getParameter('images_directory'),
                  $file
                );
                $img= new Image();
                $img->setImageName($file);
                $immobilier->addImage($img);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($immobilier);
            $entityManager->flush();

            return $this->redirectToRoute('immobilier_index');
        }

        return $this->render('immobilier/new.html.twig', [
            'immobilier' => $immobilier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="immobilier_show", methods={"GET"})
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function show(Immobilier $immobilier,Request $request,ImmobilierRepository $repository,MailerInterface $mailer):Response
    {
        //$immobilier=$repository->findOneBy();
        if (!$immobilier){
            throw new NotFoundHttpException('pas d\'annonce trouvée');
        }
        $form=$this->createForm( ImmobilierContactType::class);
        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $email= (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to($immobilier->getUser()->getEmail())
                ->subject('contact au sujet de votre annonce"'. $immobilier->getTitle().'"')
                ->htmlTemplate('emails/contact_immobilier.html.twig')
                ->context([
                    'immobilier'=>$immobilier,
                    'mail'=>$contact->get('email')->getData(),
                    'message'=>$contact->get('message')->getData()
                ]);
            $mailer->send($email);
            $this->addFlash('message','votre message a bien ete envoyé');

            return $this->redirectToRoute('immobilier_show',[
             'id'=>$immobilier->getId(),
            ]);

        }

        return $this->render('immobilier/show.html.twig', [
            'immobilier' => $immobilier,

            'form' =>$form->createView()

        ]);
    }

    /**
     * 
     * @Route("/{id}/edit", name="immobilier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Immobilier $immobilier): Response
    {
        $form = $this->createForm(ImmobilierType::class, $immobilier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on recupère les images
            $images=$form->get('image')->getData();

            //on boucle sur les images
            foreach ($images as $image){
                //on genere un nouveau nom de fichier
                $file=md5(uniqid()) . '.' .$image->guessExtension();

                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                $img= new Image();
                $img->setImageName($file);
                $immobilier->addImage($img);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('immobilier_index');
        }

        return $this->render('immobilier/edit.html.twig', [
            'immobilier' => $immobilier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="immobilier_delete", methods={"POST"})
     */
    public function delete(Request $request, Immobilier $immobilier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$immobilier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($immobilier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('immobilier_index');
    }

    /**
     * @Route ("/supprime/image/{id}", name="immobilier_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image,Request $request){
        $data= json_decode($request->getContent(),true);

        //on verifie si le token est valide
        if ($this->isCsrfTokenValid('delete' .$image->getId(), $data['_token'])){
            //on recupère le nom de l'image
            $nom=$image->getImageName();
            //on supprime le fichier
            unlink($this->getParameter('images_directory'). '/' .$nom);
            //on supprime de la base
            $em=$this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            //on repond en json
            return new JsonResponse(['success'=>1]);
        }else{
            return new JsonResponse(['error'=>'Token Invalide'], 400);
        }

    }


}