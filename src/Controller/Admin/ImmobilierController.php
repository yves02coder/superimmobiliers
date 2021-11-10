<?php

namespace App\Controller\Admin;

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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/immobilier", name="admin_immobilier_")
 *  @package App\Controller\Admin
 */
class ImmobilierController extends AbstractController
{
    /**
     * @Route("/front", name="administra")
     */
    public function index(ImmobilierRepository $immobilierRepository): Response
    {
       return $this->render('admin/immobilier/index.html.twig',[
           'immobilier' => $immobilierRepository->findAll()
       ]);


    }

    /**
     * @Route("/activer/{id}", name="activer")
     */
    public function Activer(Immobilier $immobilier):Response
    {
        $immobilier->setActive(($immobilier->getActive())?false:true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($immobilier);
        $em->flush();


        return new Response("true");
    }

}