<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216223301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_cmd (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_cart (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, idProduit INT NOT NULL, idCommande INT NOT NULL, UNIQUE INDEX UNIQ_864BAA16391C87D5 (idProduit), UNIQUE INDEX UNIQ_864BAA163D498C26 (idCommande), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA16391C87D5 FOREIGN KEY (idProduit) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA163D498C26 FOREIGN KEY (idCommande) REFERENCES commandes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cart_cmd');
        $this->addSql('DROP TABLE product_cart');
    }
}
