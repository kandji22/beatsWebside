<?php

namespace App\Controller\Admin;

use App\Entity\Albums;
use App\Entity\Contrat;
use App\Entity\Instrumentals;
use App\Entity\OrderDetail;
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
            ->setTitle('BeatsWebside');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Instrumental', 'fas fa-music', Instrumentals::class);
        yield MenuItem::linkToCrud('Album', 'fas fa-folder', Albums::class);
        yield MenuItem::linkToCrud('Contrat', 'fa fa-eye', Contrat::class);
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', OrderDetail::class);
    }
}
