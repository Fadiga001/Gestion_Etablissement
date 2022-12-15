<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221109213343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes CHANGE note_etudiant note_etudiant LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE etudiant etudiant LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes CHANGE etudiant etudiant TINYTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE note_etudiant note_etudiant TINYTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
