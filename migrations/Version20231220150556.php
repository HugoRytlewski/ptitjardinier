<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220150556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (no INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, cp INT NOT NULL, type_client INT NOT NULL, PRIMARY KEY(no)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (no INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_8B27C52B19EB6921 (client_id), PRIMARY KEY(no)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tailler (no INT AUTO_INCREMENT NOT NULL, devis_id INT DEFAULT NULL, haie_code VARCHAR(255) DEFAULT NULL, code VARCHAR(255) NOT NULL, hauteur DOUBLE PRECISION NOT NULL, longueur DOUBLE PRECISION NOT NULL, INDEX IDX_447D178841DEFADA (devis_id), INDEX IDX_447D1788B7F6B776 (haie_code), PRIMARY KEY(no)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES client (no)');
        $this->addSql('ALTER TABLE tailler ADD CONSTRAINT FK_447D178841DEFADA FOREIGN KEY (devis_id) REFERENCES devis (no)');
        $this->addSql('ALTER TABLE tailler ADD CONSTRAINT FK_447D1788B7F6B776 FOREIGN KEY (haie_code) REFERENCES haie (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE tailler DROP FOREIGN KEY FK_447D178841DEFADA');
        $this->addSql('ALTER TABLE tailler DROP FOREIGN KEY FK_447D1788B7F6B776');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE tailler');
    }
}
