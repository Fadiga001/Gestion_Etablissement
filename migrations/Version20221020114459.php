<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020114459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C8F5EA509');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CDDEAB1A3');
        $this->addSql('DROP INDEX IDX_11BA68CDDEAB1A3 ON notes');
        $this->addSql('DROP INDEX IDX_11BA68C8F5EA509 ON notes');
        $this->addSql('ALTER TABLE notes ADD classe VARCHAR(255) NOT NULL, ADD etudiant VARCHAR(255) NOT NULL, DROP classe_id, DROP etudiant_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes ADD classe_id INT DEFAULT NULL, ADD etudiant_id INT DEFAULT NULL, DROP classe, DROP etudiant');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_11BA68CDDEAB1A3 ON notes (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_11BA68C8F5EA509 ON notes (classe_id)');
    }
}
