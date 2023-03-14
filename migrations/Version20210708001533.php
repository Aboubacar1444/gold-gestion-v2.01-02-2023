<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210708001533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE operations (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, agency_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, base DOUBLE PRECISION DEFAULT NULL, poideau DOUBLE PRECISION DEFAULT NULL, poidair DOUBLE PRECISION DEFAULT NULL, densite DOUBLE PRECISION DEFAULT NULL, karat DOUBLE PRECISION DEFAULT NULL, prixu DOUBLE PRECISION DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, avance DOUBLE PRECISION DEFAULT NULL, totalm DOUBLE PRECISION DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, created_at DATETIME DEFAULT NULL, agent VARCHAR(255) DEFAULT NULL, facture VARCHAR(255) DEFAULT NULL, numero INT DEFAULT NULL, motif VARCHAR(4000) DEFAULT NULL, tempclient VARCHAR(255) DEFAULT NULL, time VARCHAR(255) NOT NULL, INDEX IDX_2814534819EB6921 (client_id), INDEX IDX_28145348CDEADB2A (agency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operations ADD CONSTRAINT FK_2814534819EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE operations ADD CONSTRAINT FK_28145348CDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE operations');
    }
}
