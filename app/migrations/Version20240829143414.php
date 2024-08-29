<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240829143414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE frais (id INT AUTO_INCREMENT NOT NULL, chauffeur_id INT DEFAULT NULL, date DATE NOT NULL, lieu VARCHAR(255) NOT NULL, heure_volant DOUBLE PRECISION NOT NULL, heures_totales DOUBLE PRECISION NOT NULL, total_frais DOUBLE PRECISION NOT NULL, INDEX IDX_25404C9885C0B3BE (chauffeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frais_detail (id INT AUTO_INCREMENT NOT NULL, frais_id INT DEFAULT NULL, type_frais_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_BC976D19BF516DC4 (frais_id), INDEX IDX_BC976D1972AE4A38 (type_frais_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_frais (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE frais ADD CONSTRAINT FK_25404C9885C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES chauffeur (id)');
        $this->addSql('ALTER TABLE frais_detail ADD CONSTRAINT FK_BC976D19BF516DC4 FOREIGN KEY (frais_id) REFERENCES frais (id)');
        $this->addSql('ALTER TABLE frais_detail ADD CONSTRAINT FK_BC976D1972AE4A38 FOREIGN KEY (type_frais_id) REFERENCES type_frais (id)');
        $this->addSql('ALTER TABLE chauffeur ADD prenom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE frais DROP FOREIGN KEY FK_25404C9885C0B3BE');
        $this->addSql('ALTER TABLE frais_detail DROP FOREIGN KEY FK_BC976D19BF516DC4');
        $this->addSql('ALTER TABLE frais_detail DROP FOREIGN KEY FK_BC976D1972AE4A38');
        $this->addSql('DROP TABLE frais');
        $this->addSql('DROP TABLE frais_detail');
        $this->addSql('DROP TABLE type_frais');
        $this->addSql('ALTER TABLE chauffeur DROP prenom');
    }
}
