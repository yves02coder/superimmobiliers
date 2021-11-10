<?php

namespace App\Controller;

use App\Entity\Immobilier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }
    /**
     * @Route("/user/immobilier/ajout", name="user_immobilier_ajout")
     */
    public function ajoutImmobilier(Request $request): Response
    {
        $immobilier = new Immobilier;
        $form = $this->createForm(ImmobilierType::class, $immobilier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $immobilier->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($immobilier);
            $em->flush();
            return  $this->redirectToRoute('user');
        }

        return $this->render('users/immobilier/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
