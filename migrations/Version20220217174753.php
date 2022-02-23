<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217174753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA163D498C26');
        $this->addSql('DROP INDEX IDX_864BAA163D498C26 ON product_cart');
        $this->addSql('ALTER TABLE product_cart ADD id_commande_id INT DEFAULT NULL, DROP idCommande');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA169AF8E3A3 FOREIGN KEY (id_commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_864BAA169AF8E3A3 ON product_cart (id_commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA169AF8E3A3');
        $this->addSql('DROP INDEX IDX_864BAA169AF8E3A3 ON product_cart');
        $this->addSql('ALTER TABLE product_cart ADD idCommande INT NOT NULL, DROP id_commande_id');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA163D498C26 FOREIGN KEY (idCommande) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_864BAA163D498C26 ON product_cart (idCommande)');
    }
}
