<?php

namespace App\Controller\Admin;

use App\Entity\Chauffeur;
use App\Entity\TypeFrais;
use App\Entity\Frais;
use App\Entity\FraisDetail;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Redirige vers la page d'accueil de l'administration
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        ->setTitle('Gestion des Frais des Chauffeurs');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Chauffeur', 'fas fa-users', Chauffeur::class);
        yield MenuItem::linkToCrud('Types de Frais', 'fas fa-list', TypeFrais::class);
        yield MenuItem::linkToCrud('Frais', 'fas fa-euro-sign', Frais::class);
        yield MenuItem::linkToCrud('Détails des Frais', 'fas fa-file-invoice', FraisDetail::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
