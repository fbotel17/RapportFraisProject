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


    
}

