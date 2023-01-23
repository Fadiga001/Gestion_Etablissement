<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221221222149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961A8F5EA509');
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961ABAB22EE9');
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961ADDEAB1A3');
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961AF46CD258');
        $this->addSql('DROP INDEX IDX_761C961ABAB22EE9 ON noter');
        $this->addSql('DROP INDEX IDX_761C961ADDEAB1A3 ON noter');
        $this->addSql('DROP INDEX IDX_761C961A8F5EA509 ON noter');
        $this->addSql('DROP INDEX IDX_761C961AF46CD258 ON noter');
        $this->addSql('ALTER TABLE noter ADD classes VARCHAR(255) DEFAULT NULL, ADD etudiants VARCHAR(255) DEFAULT NULL, ADD prof VARCHAR(255) DEFAULT NULL, ADD matieres VARCHAR(255) DEFAULT NULL, DROP etudiant_id, DROP classe_id, DROP matiere_id, DROP professeur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noter ADD etudiant_id INT DEFAULT NULL, ADD classe_id INT DEFAULT NULL, ADD matiere_id INT DEFAULT NULL, ADD professeur_id INT DEFAULT NULL, DROP classes, DROP etudiants, DROP prof, DROP matieres');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961A8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961ABAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeurs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961ADDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961AF46CD258 FOREIGN KEY (matiere_id) REFERENCES matieres (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_761C961ABAB22EE9 ON noter (professeur_id)');
        $this->addSql('CREATE INDEX IDX_761C961ADDEAB1A3 ON noter (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_761C961A8F5EA509 ON noter (classe_id)');
        $this->addSql('CREATE INDEX IDX_761C961AF46CD258 ON noter (matiere_id)');
    }
}
