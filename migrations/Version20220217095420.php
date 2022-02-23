<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217095420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DCD11A2CF');
        $this->addSql('DROP INDEX IDX_6EEAA67DCD11A2CF ON commande');
        $this->addSql('ALTER TABLE commande DROP produits_id');
        $this->addSql('ALTER TABLE product_cart ADD idCommande INT NOT NULL');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA163D498C26 FOREIGN KEY (idCommande) REFERENCES commande (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_864BAA163D498C26 ON product_cart (idCommande)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD produits_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DCD11A2CF FOREIGN KEY (produits_id) REFERENCES product_cart (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DCD11A2CF ON commande (produits_id)');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA163D498C26');
        $this->addSql('DROP INDEX UNIQ_864BAA163D498C26 ON product_cart');
        $this->addSql('ALTER TABLE product_cart DROP idCommande');
    }
}
