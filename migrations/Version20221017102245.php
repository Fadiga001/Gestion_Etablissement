<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221017102245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, semestre VARCHAR(255) NOT NULL, coefficient INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notes_etudiant (notes_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_1D786142FC56F556 (notes_id), INDEX IDX_1D786142DDEAB1A3 (etudiant_id), PRIMARY KEY(notes_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notes_etudiant ADD CONSTRAINT FK_1D786142FC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notes_etudiant ADD CONSTRAINT FK_1D786142DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classe ADD notes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96FC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id)');
        $this->addSql('CREATE INDEX IDX_8F87BF96FC56F556 ON classe (notes_id)');
        $this->addSql('ALTER TABLE matieres ADD notes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matieres ADD CONSTRAINT FK_8D9773D2FC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id)');
        $this->addSql('CREATE INDEX IDX_8D9773D2FC56F556 ON matieres (notes_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96FC56F556');
        $this->addSql('ALTER TABLE matieres DROP FOREIGN KEY FK_8D9773D2FC56F556');
        $this->addSql('ALTER TABLE notes_etudiant DROP FOREIGN KEY FK_1D786142FC56F556');
        $this->addSql('ALTER TABLE notes_etudiant DROP FOREIGN KEY FK_1D786142DDEAB1A3');
        $this->addSql('DROP TABLE notes');
        $this->addSql('DROP TABLE notes_etudiant');
        $this->addSql('DROP INDEX IDX_8D9773D2FC56F556 ON matieres');
        $this->addSql('ALTER TABLE matieres DROP notes_id');
        $this->addSql('DROP INDEX IDX_8F87BF96FC56F556 ON classe');
        $this->addSql('ALTER TABLE classe DROP notes_id');
    }
}
