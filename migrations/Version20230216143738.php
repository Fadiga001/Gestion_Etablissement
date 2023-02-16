<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216143738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noter ADD matricules_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE noter ADD CONSTRAINT FK_761C961A9781E376 FOREIGN KEY (matricules_id) REFERENCES etudiant (id)');
        $this->addSql('CREATE INDEX IDX_761C961A9781E376 ON noter (matricules_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noter DROP FOREIGN KEY FK_761C961A9781E376');
        $this->addSql('DROP INDEX IDX_761C961A9781E376 ON noter');
        $this->addSql('ALTER TABLE noter DROP matricules_id');
    }
}
