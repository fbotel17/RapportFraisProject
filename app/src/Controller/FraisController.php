<?php

namespace App\Controller;

use App\Entity\Frais;
use App\Form\FraisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FraisController extends AbstractController
{
    #[Route('/frais/ajouter', name: 'ajouter_frais')]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $frais = new Frais();
        $form = $this->createForm(FraisType::class, $frais);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Convertir `heure_volant` et `heures_totales` en float (heures décimales)
            $heureVolantTime = $form->get('heureVolantTime')->getData();
            $heuresTotalesTime = $form->get('heuresTotalesTime')->getData();

            $frais->setHeureVolantTime($heureVolantTime);
            $frais->setHeuresTotalesTime($heuresTotalesTime);

            // Calculer le total des frais
            $totalFrais = 0;
            if ($frais->getPetitDejeuner()) {
                $totalFrais += 8.65; // Valeur du petit déjeuner
            }
            if ($frais->getRepasMidi()) {
                $totalFrais += 15.96; // Valeur du repas midi
            }
            if ($frais->getRepasSoir()) {
                $totalFrais += 15.96; // Valeur du repas soir
            }
            if ($frais->getNuit()) {
                $totalFrais += 35.05; // Valeur de la nuitée
            }

            $frais->setTotalFrais($totalFrais);

            $entityManager->persist($frais);
            $entityManager->flush();

            $this->addFlash('success', 'Frais ajoutés avec succès !');

            return $this->redirectToRoute('ajouter_frais');
        }

        return $this->render('frais/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
