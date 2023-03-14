<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210712012034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transfert (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, transagency_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, frais DOUBLE PRECISION DEFAULT NULL, tel VARCHAR(255) NOT NULL, agency VARCHAR(255) DEFAULT NULL, agent VARCHAR(255) NOT NULL, transagent VARCHAR(255) NOT NULL, facture VARCHAR(255) DEFAULT NULL, INDEX IDX_1E4EACBB19EB6921 (client_id), INDEX IDX_1E4EACBB7F18E010 (transagency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transfert ADD CONSTRAINT FK_1E4EACBB19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transfert ADD CONSTRAINT FK_1E4EACBB7F18E010 FOREIGN KEY (transagency_id) REFERENCES agency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transfert');
    }
}
