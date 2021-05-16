<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Immobilier;
use App\Entity\Image;
use App\Entity\ImmobilierSearch;
use App\Form\ContactType;
use App\Form\ImmobilierSearchType;
use App\Form\ImmobilierType;
use App\Form\SearchAnnonceType;
use App\Repository\ImmobilierRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/*use Symfony\Component\DomCrawler\Image;*/

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $search= new ImmobilierSearch();
        $form= $this->createForm(ImmobilierSearchType::class,$search);
        $form->handleRequest($request);

        $pagination=$paginator->paginate(
            $immobilierRepository->findAlL($search),
            $request->query->getInt('page',1),4
        );

       /* $form= $this->createForm(SearchAnnonceType::class);
        $search=$form->handleRequest($request);*/

        /*if ($form->isSubmitted() && $form->isValid()){
          $pagination=$paginator->$this->search($search->get('mots')
          ->getData());
        }*/


        return $this->render('immobilier/index.html.twig', [
            'pagination' => $pagination,
            'form'=>$form->createView()

        ]);

    }

    /**
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
     */
    public function show(Immobilier $immobilier): Response
    {
        $contact= new Contact();
        $contact->setImmobilier($immobilier);
        $form=$this->createForm( ContactType::class,$contact);

        return $this->render('immobilier/show.html.twig', [
            'immobilier' => $immobilier,
            'form' =>$form->createView()

        ]);
    }

    /**
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
