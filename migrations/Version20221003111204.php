<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221003111204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classe_matieres (classe_id INT NOT NULL, matieres_id INT NOT NULL, INDEX IDX_B759BB7C8F5EA509 (classe_id), INDEX IDX_B759BB7C82350831 (matieres_id), PRIMARY KEY(classe_id, matieres_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classe_matieres ADD CONSTRAINT FK_B759BB7C8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classe_matieres ADD CONSTRAINT FK_B759BB7C82350831 FOREIGN KEY (matieres_id) REFERENCES matieres (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe_matieres DROP FOREIGN KEY FK_B759BB7C8F5EA509');
        $this->addSql('ALTER TABLE classe_matieres DROP FOREIGN KEY FK_B759BB7C82350831');
        $this->addSql('DROP TABLE classe_matieres');
    }
}
