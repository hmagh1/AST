<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250716100523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrateur_ucac (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE astreignable (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, telephone VARCHAR(20) NOT NULL, seniorite VARCHAR(50) NOT NULL, direction VARCHAR(100) NOT NULL, disponible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drh (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_courante (id INT AUTO_INCREMENT NOT NULL, astreignable_id INT NOT NULL, date DATE NOT NULL, details LONGTEXT NOT NULL, INDEX IDX_F03F25FC58F12170 (astreignable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning_astreinte (id INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, theme VARCHAR(100) NOT NULL, statut VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning_astreinte_binome (planning_astreinte_id INT NOT NULL, astreignable_id INT NOT NULL, INDEX IDX_6721DCB1B19E4A04 (planning_astreinte_id), INDEX IDX_6721DCB158F12170 (astreignable_id), PRIMARY KEY(planning_astreinte_id, astreignable_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_fait (id INT AUTO_INCREMENT NOT NULL, astreignable_id INT NOT NULL, date DATE NOT NULL, heures_effectuees INT NOT NULL, valide TINYINT(1) NOT NULL, INDEX IDX_5B0807F358F12170 (astreignable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE main_courante ADD CONSTRAINT FK_F03F25FC58F12170 FOREIGN KEY (astreignable_id) REFERENCES astreignable (id)');
        $this->addSql('ALTER TABLE planning_astreinte_binome ADD CONSTRAINT FK_6721DCB1B19E4A04 FOREIGN KEY (planning_astreinte_id) REFERENCES planning_astreinte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning_astreinte_binome ADD CONSTRAINT FK_6721DCB158F12170 FOREIGN KEY (astreignable_id) REFERENCES astreignable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_fait ADD CONSTRAINT FK_5B0807F358F12170 FOREIGN KEY (astreignable_id) REFERENCES astreignable (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE main_courante DROP FOREIGN KEY FK_F03F25FC58F12170');
        $this->addSql('ALTER TABLE planning_astreinte_binome DROP FOREIGN KEY FK_6721DCB1B19E4A04');
        $this->addSql('ALTER TABLE planning_astreinte_binome DROP FOREIGN KEY FK_6721DCB158F12170');
        $this->addSql('ALTER TABLE service_fait DROP FOREIGN KEY FK_5B0807F358F12170');
        $this->addSql('DROP TABLE administrateur_ucac');
        $this->addSql('DROP TABLE astreignable');
        $this->addSql('DROP TABLE drh');
        $this->addSql('DROP TABLE main_courante');
        $this->addSql('DROP TABLE planning_astreinte');
        $this->addSql('DROP TABLE planning_astreinte_binome');
        $this->addSql('DROP TABLE service_fait');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
