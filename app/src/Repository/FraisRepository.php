<?php

// src/Repository/FraisRepository.php

namespace App\Repository;

use App\Entity\Frais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

class FraisRepository extends ServiceEntityRepository
{
    private $conn;

    public function __construct(ManagerRegistry $registry, Connection $conn)
    {
        parent::__construct($registry, Frais::class);
        $this->conn = $conn;
    }

    /**
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return Frais[]
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.date BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les mois distincts où il existe des frais.
     */
    public function findDistinctMonths(): array
    {
        $sql = "
            SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS month
            FROM frais
            ORDER BY month DESC
        ";

        $result = $this->conn->fetchAllAssociative($sql);

        // Assurez-vous que le résultat est bien un tableau
        return $result;
    }




    /**
     * Retourne les frais pour un mois spécifique.
     */
    public function findByMonth(int $year, int $month): array
    {
        $sql = "
            SELECT * 
            FROM frais 
            WHERE YEAR(date) = :year 
            AND MONTH(date) = :month
            ORDER BY date DESC
        ";

        return $this->conn->fetchAllAssociative($sql, [
            'year' => $year,
            'month' => $month,
        ]);
    }

    public function getMonthlyStatistics(): array
    {
        $qb = $this->createQueryBuilder('f')
            ->select('
                EXTRACT(MONTH FROM f.date) AS month, 
                COUNT(CASE WHEN f.petit_dejeuner = true THEN 1 END) AS petit_dejeuner,
                COUNT(CASE WHEN f.repas_midi = true THEN 1 END) AS repas_midi,
                COUNT(CASE WHEN f.repas_soir = true THEN 1 END) AS repas_soir,
                COUNT(CASE WHEN f.nuit = true THEN 1 END) AS nuit,
                COUNT(CASE WHEN f.dimanche = true THEN 1 END) AS dimanche,
                COUNT(CASE WHEN f.total_frais = true THEN 1 END) AS total_frais
            ')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery();

        return $qb->getResult();
    }

    public function getAllFrais(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findFraisBySearchTerm($searchTerm)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM frais f
            WHERE
                DATE_FORMAT(f.date, "%Y-%m") LIKE :searchTerm OR
                f.heure_volant >= :searchTerm OR
                f.heures_totales >= :searchTerm OR
                f.total_frais >= :searchTerm
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['searchTerm' => '%'.$searchTerm.'%']);

        return $stmt->fetchAllAssociative();
    }


    
}

