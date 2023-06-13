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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(AlbumsCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('BeatsWebside');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Album', 'fas fa-folder', Albums::class);
        yield MenuItem::linkToCrud('Instrumental', 'fas fa-music', Instrumentals::class);

        yield MenuItem::linkToCrud('Contrat', 'fa fa-eye', Contrat::class);
        yield MenuItem::linkToCrud('Detail Transaction', 'fa fa-shopping-cart', OrderDetail::class);
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-users', User::class);

    }
}
