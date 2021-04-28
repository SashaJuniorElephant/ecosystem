<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516104534 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE history_logs DROP CONSTRAINT fk_fbbab62053c55f64');
        $this->addSql('DROP INDEX idx_fbbab62053c55f64');
        $this->addSql('ALTER TABLE history_logs RENAME COLUMN map_id TO map_state_id');
        $this->addSql('ALTER TABLE history_logs ADD CONSTRAINT FK_FBBAB620672A0AE6 FOREIGN KEY (map_state_id) REFERENCES map_state (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FBBAB620672A0AE6 ON history_logs (map_state_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE history_logs DROP CONSTRAINT FK_FBBAB620672A0AE6');
        $this->addSql('DROP INDEX IDX_FBBAB620672A0AE6');
        $this->addSql('ALTER TABLE history_logs RENAME COLUMN map_state_id TO map_id');
        $this->addSql('ALTER TABLE history_logs ADD CONSTRAINT fk_fbbab62053c55f64 FOREIGN KEY (map_id) REFERENCES map_state (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fbbab62053c55f64 ON history_logs (map_id)');
    }
}
