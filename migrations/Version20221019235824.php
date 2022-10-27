<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221019235824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes_etudiant DROP FOREIGN KEY FK_1D786142DDEAB1A3');
        $this->addSql('ALTER TABLE notes_etudiant DROP FOREIGN KEY FK_1D786142FC56F556');
        $this->addSql('DROP TABLE notes_etudiant');
        $this->addSql('ALTER TABLE notes ADD etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('CREATE INDEX IDX_11BA68CDDEAB1A3 ON notes (etudiant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notes_etudiant (notes_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_1D786142DDEAB1A3 (etudiant_id), INDEX IDX_1D786142FC56F556 (notes_id), PRIMARY KEY(notes_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE notes_etudiant ADD CONSTRAINT FK_1D786142DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notes_etudiant ADD CONSTRAINT FK_1D786142FC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CDDEAB1A3');
        $this->addSql('DROP INDEX IDX_11BA68CDDEAB1A3 ON notes');
        $this->addSql('ALTER TABLE notes DROP etudiant_id');
    }
}
