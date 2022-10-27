<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220927094902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matieres ADD prof_id INT NOT NULL');
        $this->addSql('ALTER TABLE matieres ADD CONSTRAINT FK_8D9773D2ABC1F7FE FOREIGN KEY (prof_id) REFERENCES professeurs (id)');
        $this->addSql('CREATE INDEX IDX_8D9773D2ABC1F7FE ON matieres (prof_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matieres DROP FOREIGN KEY FK_8D9773D2ABC1F7FE');
        $this->addSql('DROP INDEX IDX_8D9773D2ABC1F7FE ON matieres');
        $this->addSql('ALTER TABLE matieres DROP prof_id');
    }
}
