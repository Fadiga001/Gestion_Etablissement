<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220927095722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matieres ADD type_matiere_id INT NOT NULL');
        $this->addSql('ALTER TABLE matieres ADD CONSTRAINT FK_8D9773D2E96F047D FOREIGN KEY (type_matiere_id) REFERENCES type_matieres (id)');
        $this->addSql('CREATE INDEX IDX_8D9773D2E96F047D ON matieres (type_matiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matieres DROP FOREIGN KEY FK_8D9773D2E96F047D');
        $this->addSql('DROP INDEX IDX_8D9773D2E96F047D ON matieres');
        $this->addSql('ALTER TABLE matieres DROP type_matiere_id');
    }
}
