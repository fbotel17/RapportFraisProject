<?php

namespace App\Controller;

use App\Repository\FraisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class StatistiqueController extends AbstractController
{
    #[Route('/statistiques', name: 'statistiques')]
    public function index(FraisRepository $fraisRepository): Response
    {
        // Calculer le début et la fin du mois courant
        $dateDebutMois = new DateTime('first day of this month');
        $dateFinMois = new DateTime('last day of this month');

        $dateDebutMois->setTime(0, 0, 0);  // Début du mois à minuit
        $dateFinMois->setTime(23, 59, 59); // Fin du mois à 23h59m59s

        // Récupérer les frais du mois courant
        $frais = $fraisRepository->findByDateRange($dateDebutMois, $dateFinMois);

        // Calculer le total des frais et le total des heures
        $totalFrais = 0;
        $totalMinutes = 0;

        foreach ($frais as $fraisItem) {
            $totalFrais += $fraisItem->getTotalFrais();
            $totalMinutes += $fraisItem->getHeuresTotales();
        }

        // Convertir les minutes en heures et minutes
        $totalHeures = floor($totalMinutes / 60);
        $totalMinutes = $totalMinutes % 60;

        return $this->render('statistiques/index.html.twig', [
            'totalFrais' => $totalFrais,
            'totalHeures' => sprintf('%dh %02dmin', $totalHeures, $totalMinutes),
        ]);
    }
}
