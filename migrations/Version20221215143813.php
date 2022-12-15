<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215143813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CA873A5C6 FOREIGN KEY (etudiants_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C9E225B24 FOREIGN KEY (classes_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C82350831 FOREIGN KEY (matieres_id) REFERENCES matieres (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CBAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeurs (id)');
        $this->addSql('CREATE INDEX IDX_11BA68CA873A5C6 ON notes (etudiants_id)');
        $this->addSql('CREATE INDEX IDX_11BA68C9E225B24 ON notes (classes_id)');
        $this->addSql('CREATE INDEX IDX_11BA68C82350831 ON notes (matieres_id)');
        $this->addSql('CREATE INDEX IDX_11BA68CBAB22EE9 ON notes (professeur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CA873A5C6');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C9E225B24');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C82350831');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CBAB22EE9');
        $this->addSql('DROP INDEX IDX_11BA68CA873A5C6 ON notes');
        $this->addSql('DROP INDEX IDX_11BA68C9E225B24 ON notes');
        $this->addSql('DROP INDEX IDX_11BA68C82350831 ON notes');
        $this->addSql('DROP INDEX IDX_11BA68CBAB22EE9 ON notes');
    }
}
