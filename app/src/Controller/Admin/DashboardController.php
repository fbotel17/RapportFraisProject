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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;


class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(FraisCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        ->setTitle('Gestion des Frais des Chauffeurs');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Accueil', 'fa fa-home', $this->generateUrl('statistiques'));
        yield MenuItem::linkToCrud('Chauffeur', 'fas fa-users', Chauffeur::class);
        yield MenuItem::linkToCrud('Types de Frais', 'fas fa-list', TypeFrais::class);
        yield MenuItem::linkToCrud('Frais', 'fas fa-euro-sign', Frais::class);
        yield MenuItem::linkToCrud('Détails des Frais', 'fas fa-file-invoice', FraisDetail::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
