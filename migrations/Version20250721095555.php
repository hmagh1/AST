<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721095555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur_ucac ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE administrateur_ucac ADD CONSTRAINT FK_F32BC0D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F32BC0D3A76ED395 ON administrateur_ucac (user_id)');
        $this->addSql('ALTER TABLE user ADD administrateur_ucac_id INT DEFAULT NULL, ADD astreignable_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498EC860A4 FOREIGN KEY (administrateur_ucac_id) REFERENCES administrateur_ucac (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64958F12170 FOREIGN KEY (astreignable_id) REFERENCES astreignable (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498EC860A4 ON user (administrateur_ucac_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64958F12170 ON user (astreignable_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur_ucac DROP FOREIGN KEY FK_F32BC0D3A76ED395');
        $this->addSql('DROP INDEX UNIQ_F32BC0D3A76ED395 ON administrateur_ucac');
        $this->addSql('ALTER TABLE administrateur_ucac DROP user_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498EC860A4');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64958F12170');
        $this->addSql('DROP INDEX UNIQ_8D93D6498EC860A4 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D64958F12170 ON user');
        $this->addSql('ALTER TABLE user DROP administrateur_ucac_id, DROP astreignable_id');
    }
}
