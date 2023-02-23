<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221091218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant CHANGE sexe sexe VARCHAR(255) DEFAULT NULL, CHANGE nationalite nationalite VARCHAR(255) DEFAULT NULL, CHANGE personne_acontacter personne_acontacter VARCHAR(255) DEFAULT NULL, CHANGE adresse_de_personne_acontacter adresse_de_personne_acontacter VARCHAR(255) DEFAULT NULL, CHANGE telephone_de_personne_acontacter telephone_de_personne_acontacter VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant CHANGE sexe sexe VARCHAR(255) NOT NULL, CHANGE nationalite nationalite VARCHAR(255) NOT NULL, CHANGE personne_acontacter personne_acontacter VARCHAR(255) NOT NULL, CHANGE adresse_de_personne_acontacter adresse_de_personne_acontacter VARCHAR(255) NOT NULL, CHANGE telephone_de_personne_acontacter telephone_de_personne_acontacter VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL');
    }
}
