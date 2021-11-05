<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Immobilier;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {


        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SuperImmobilier');
    }

    public function configureMenuItems(): iterable
    {
        return[
            MenuItem::section('user'),
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Category', 'fa fa-folder-o',Category::class),
            MenuItem::linkToCrud('Image','fa fa-picture-o',Image::class),
            MenuItem::linkToCrud('Immobilier','fa fa-building-o',Immobilier::class),
            MenuItem::linkToCrud('User','fa fa-user-circle',User::class),
        ];


    }
}
