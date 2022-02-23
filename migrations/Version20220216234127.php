<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216234127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, total_paiment DOUBLE PRECISION NOT NULL, date VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA163D498C26');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA163D498C26 FOREIGN KEY (idCommande) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA163D498C26');
        $this->addSql('DROP TABLE commande');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA163D498C26');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA163D498C26 FOREIGN KEY (idCommande) REFERENCES commandes (id)');
    }
}
