<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FraisRepository;
use Symfony\Component\HttpFoundation\Request;

class StatistiquesGlobalesController extends AbstractController
{
    #[Route('/statistiques/globales', name: 'app_statistiques_globales')]
    public function index(FraisRepository $fraisRepository): Response
    {
        $fraisList = $fraisRepository->getAllFrais();
        $statistiques = [];

        foreach ($fraisList as $frais) {
            $date = $frais->getDate(); // Obtenir l'objet DateTime
            $monthYearKey = $date->format('Y-m'); // Clé au format "année-mois"

            if (!isset($statistiques[$monthYearKey])) {
                $statistiques[$monthYearKey] = [
                    'date' => $date,  // Ajoutez la date ici
                    'petit_dejeuner' => 0,
                    'nuit' => 0,
                    'dimanche' => 0,
                    'repas_midi' => 0,
                    'repas_soir' => 0,
                    'total_heures_volant' => 0,
                    'total_heures_totales' => 0,
                    'total_frais' => 0,
                ];
            }

            if ($frais->getRepasMidi()) {
                $statistiques[$monthYearKey]['repas_midi'] += 1;
            }

            if ($frais->getRepasSoir()) {
                $statistiques[$monthYearKey]['repas_soir'] += 1;
            }

            $statistiques[$monthYearKey]['total_heures_volant'] += $frais->getHeureVolant();
            $statistiques[$monthYearKey]['total_heures_totales'] += $frais->getHeuresTotales();

            $statistiques[$monthYearKey]['total_frais'] += $frais->getTotalFrais();
        }

        return $this->render('statistiques_globales/index.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }

    #[Route('/statistiques/globales/filter', name: 'app_statistiques_filter', methods: ['POST'])]
    public function filter(Request $request, FraisRepository $fraisRepository): Response
    {
        $searchTerm = $request->request->get('searchTerm');
        $statistiques = $this->getStatistiques($fraisRepository, $searchTerm);

        return $this->render('statistiques_globales/index.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }

    private function getStatistiques(FraisRepository $fraisRepository, $searchTerm = null)
    {
        $queryBuilder = $fraisRepository->createQueryBuilder('f');

        if ($searchTerm) {
            $queryBuilder->andWhere('
                f.date LIKE :searchTerm OR
                f.heure_volant >= :searchTerm OR
                f.heures_totales >= :searchTerm OR
                f.total_frais >= :searchTerm
            ')
            ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }

        $fraisList = $queryBuilder->getQuery()->getResult();
        $statistiques = [];

        foreach ($fraisList as $frais) {
            $month = $frais->getDate()->format('m');

            if (!isset($statistiques[$month])) {
                $statistiques[$month] = [
                    'petit_dejeuner' => 0,
                    'nuit' => 0,
                    'dimanche' => 0,
                    'repas_midi' => 0,
                    'repas_soir' => 0,
                    'total_heures_volant' => 0,
                    'total_heures_totales' => 0,
                    'total_frais' => 0,
                    'date' => $frais->getDate(), // Pour obtenir le mois et l'année
                ];
            }

            if ($frais->getRepasMidi()) {
                $statistiques[$month]['repas_midi'] += 1;
            }

            if ($frais->getRepasSoir()) {
                $statistiques[$month]['repas_soir'] += 1;
            }

            if ($frais->getPetitDejeuner()) {
                $statistiques[$month]['petit_dejeuner'] += 1;
            }

            if ($frais->getNuit()) {
                $statistiques[$month]['nuit'] += 1;
            }

            if ($frais->getDimanche()) {
                $statistiques[$month]['dimanche'] += 1;
            }

            $statistiques[$month]['total_heures_volant'] += $frais->getHeureVolant();
            $statistiques[$month]['total_heures_totales'] += $frais->getHeuresTotales();
            $statistiques[$month]['total_frais'] += $frais->getTotalFrais();
        }

        return $statistiques;
    }

    
}
