<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216102753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634F347EFB');
        $this->addSql('DROP INDEX IDX_497DD634F347EFB ON categorie');
        $this->addSql('ALTER TABLE categorie DROP produit_id');
        $this->addSql('ALTER TABLE produit ADD quantite INT NOT NULL, ADD image VARCHAR(255) NOT NULL, CHANGE prix prix INT NOT NULL, CHANGE quantity cathegorie_id INT NOT NULL, CHANGE nom designation VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2775654620 FOREIGN KEY (cathegorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2775654620 ON produit (cathegorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_497DD634F347EFB ON categorie (produit_id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2775654620');
        $this->addSql('DROP INDEX IDX_29A5EC2775654620 ON produit');
        $this->addSql('ALTER TABLE produit ADD nom VARCHAR(255) NOT NULL, ADD quantity INT NOT NULL, DROP cathegorie_id, DROP designation, DROP quantite, DROP image, CHANGE prix prix DOUBLE PRECISION NOT NULL');
    }
}
