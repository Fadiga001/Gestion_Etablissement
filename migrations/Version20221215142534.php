<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215142534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CF46CD258');
        $this->addSql('DROP INDEX IDX_11BA68CF46CD258 ON notes');
        $this->addSql('ALTER TABLE notes ADD classes_id INT DEFAULT NULL, ADD matieres_id INT DEFAULT NULL, ADD professeur_id INT DEFAULT NULL, DROP coefficient, DROP note_etudiant, DROP classe, DROP etudiant, CHANGE matiere_id etudiants_id INT DEFAULT NULL');
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
        $this->addSql('ALTER TABLE notes ADD matiere_id INT DEFAULT NULL, ADD coefficient INT NOT NULL, ADD note_etudiant LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD classe VARCHAR(255) NOT NULL, ADD etudiant LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', DROP etudiants_id, DROP classes_id, DROP matieres_id, DROP professeur_id');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matieres (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_11BA68CF46CD258 ON notes (matiere_id)');
    }
}
