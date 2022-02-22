<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220220213844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cmnt ADD blog_id INT NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD nickname VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD rgpd TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE cmnt ADD CONSTRAINT FK_E4CE2C8EDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('CREATE INDEX IDX_E4CE2C8EDAE07E97 ON cmnt (blog_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cmnt DROP FOREIGN KEY FK_E4CE2C8EDAE07E97');
        $this->addSql('DROP INDEX IDX_E4CE2C8EDAE07E97 ON cmnt');
        $this->addSql('ALTER TABLE cmnt DROP blog_id, DROP email, DROP nickname, DROP created_at, DROP rgpd');
    }
}
