<?php

namespace App\Controller;

use App\Entity\Frais;
use App\Form\FraisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FraisRepository;

class HistoriqueController extends AbstractController
{
    #[Route('/historique', name: 'historique')]
    public function index(Request $request, FraisRepository $fraisRepository): Response
    {
        // Récupérer tous les mois distincts
        $months = $fraisRepository->findDistinctMonths();

        // Récupérer le mois actuel
        $currentMonth = date('Y-m');

        // Récupérer le mois sélectionné (ou le mois actuel par défaut)
        $selectedMonth = $request->query->get('month', $currentMonth);

        // Extraire année et mois
        list($year, $month) = explode('-', $selectedMonth);

        // Récupérer les frais pour le mois sélectionné
        $fraisList = $fraisRepository->findByMonth((int)$year, (int)$month);

        // Si le mois sélectionné n'a pas de données, prendre le dernier mois enregistré
        if (empty($fraisList) && !empty($months)) {
            // Récupérer le dernier mois enregistré
            $lastMonth = end($months)['month']; // Assurez-vous que 'month' est la clé correcte
            list($year, $month) = explode('-', $lastMonth);
            $selectedMonth = $lastMonth;

            // Récupérer les frais pour le dernier mois enregistré
            $fraisList = $fraisRepository->findByMonth((int)$year, (int)$month);
        }

        // Convertir les temps en heures et minutes
        foreach ($fraisList as &$frais) {
            $frais['heure_volant'] = intdiv($frais['heure_volant'], 60) . 'h ' . ($frais['heure_volant'] % 60) . 'm';
            $frais['heures_totales'] = intdiv($frais['heures_totales'], 60) . 'h ' . ($frais['heures_totales'] % 60) . 'm';
        }

        return $this->render('historique/index.html.twig', [
            'months' => $months,
            'selectedMonth' => $selectedMonth,
            'fraisList' => $fraisList,
        ]);
    }



    #[Route('/historique/{id}', name: 'historique_detail')]
public function detail(Frais $frais, Request $request, EntityManagerInterface $entityManager): Response
{
    // Convertir le temps en minutes en un objet DateTime pour l'auto-remplissage du formulaire
    $heureVolantMinutes = $frais->getHeureVolant();
    $heuresTotalesMinutes = $frais->getHeuresTotales();

    // Créer des objets DateTime à partir du temps en minutes
    $heureVolantTime = null;
    if ($heureVolantMinutes !== null) {
        $hours = intdiv($heureVolantMinutes, 60);
        $minutes = $heureVolantMinutes % 60;
        $heureVolantTime = (new \DateTime())->setTime($hours, $minutes);
        $frais->setHeureVolantTime($heureVolantTime); // Mettre à jour l'entité avec l'objet DateTime
    }

    $heuresTotalesTime = null;
    if ($heuresTotalesMinutes !== null) {
        $hours = intdiv($heuresTotalesMinutes, 60);
        $minutes = $heuresTotalesMinutes % 60;
        $heuresTotalesTime = (new \DateTime())->setTime($hours, $minutes);
        $frais->setHeuresTotalesTime($heuresTotalesTime); // Mettre à jour l'entité avec l'objet DateTime
    }

    // Créer le formulaire
    $form = $this->createForm(FraisType::class, $frais);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer les heures et minutes du formulaire
        $heureVolantTime = $form->get('heureVolantTime')->getData();
        $heuresTotalesTime = $form->get('heuresTotalesTime')->getData();

        // Convertir les heures et minutes en minutes
        $totalHeureVolant = $heureVolantTime ? ($heureVolantTime->format('H') * 60 + $heureVolantTime->format('i')) : 0;
        $totalHeuresTotales = $heuresTotalesTime ? ($heuresTotalesTime->format('H') * 60 + $heuresTotalesTime->format('i')) : 0;

        $frais->setHeureVolant($totalHeureVolant);
        $frais->setHeuresTotales($totalHeuresTotales);

        // Calculer le total des frais
        $totalFrais = 0;
        if ($frais->getPetitDejeuner()) {
            $totalFrais += 8.65;
        }
        if ($frais->getRepasMidi()) {
            $totalFrais += 15.96;
        }
        if ($frais->getRepasSoir()) {
            $totalFrais += 15.96;
        }
        if ($frais->getNuit()) {
            $totalFrais += 35.05;
        }

        $frais->setTotalFrais($totalFrais);

        $entityManager->persist($frais);
        $entityManager->flush();

        $this->addFlash('success', 'Données modifiées avec succès !');

        return $this->redirectToRoute('historique');
    }

    return $this->render('historique/detail.html.twig', [
        'frais' => $frais,
        'form' => $form->createView(),
    ]);
}




    #[Route('/historique/{id}/delete', name: 'historique_delete', methods: ['POST'])]
    public function delete(Frais $frais, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $frais->getId(), $request->request->get('_token'))) {
            $entityManager->remove($frais);
            $entityManager->flush();

            $this->addFlash('success', 'Donnée supprimée avec succès !');
        }

        return $this->redirectToRoute('historique');
    }
}
