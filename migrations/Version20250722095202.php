<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722095202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur_ucac DROP FOREIGN KEY FK_F32BC0D3A76ED395');
        $this->addSql('DROP INDEX UNIQ_F32BC0D3A76ED395 ON administrateur_ucac');
        $this->addSql('ALTER TABLE administrateur_ucac DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur_ucac ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE administrateur_ucac ADD CONSTRAINT FK_F32BC0D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F32BC0D3A76ED395 ON administrateur_ucac (user_id)');
    }
}
