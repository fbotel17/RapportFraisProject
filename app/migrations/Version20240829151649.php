<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240829151649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE frais ADD petit_dejeuner TINYINT(1) NOT NULL, ADD repas_midi TINYINT(1) NOT NULL, ADD repas_soir TINYINT(1) NOT NULL, ADD nuit TINYINT(1) NOT NULL, ADD dimanche TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE frais DROP petit_dejeuner, DROP repas_midi, DROP repas_soir, DROP nuit, DROP dimanche');
    }
}
