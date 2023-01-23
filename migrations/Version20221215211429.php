<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215211429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE noter (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, classe_id INT DEFAULT NULL, matiere_id INT DEFAULT NULL, professeur_id INT DEFAULT NULL, semestre VARCHAR(255) NOT NULL, note_etudiant DOUBLE PRECISION NOT NULL, date_jour DATE NOT NULL, INDEX IDX_761C961ADDEAB1A3 (etudiant_id), INDEX IDX_761C961A8F5EA509 (classe_id), INDEX IDX_761C961AF46CD258 (matiere_id), INDEX IDX_761C961ABAB22EE9 (professeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961ADDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961A8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961AF46CD258 FOREIGN KEY (matiere_id) REFERENCES matieres (id)');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961ABAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeurs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961ADDEAB1A3');
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961A8F5EA509');
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961AF46CD258');
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961ABAB22EE9');
        $this->addSql('DROP TABLE noter');
    }
}
