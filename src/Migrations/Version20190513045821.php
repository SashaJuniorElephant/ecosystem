<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513045821 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE visitor_logs DROP CONSTRAINT fk_e030d54672a0ae6');
        $this->addSql('DROP INDEX idx_e030d54672a0ae6');
        $this->addSql('ALTER TABLE visitor_logs DROP map_state_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE visitor_logs ADD map_state_id INT NOT NULL');
        $this->addSql('ALTER TABLE visitor_logs ADD CONSTRAINT fk_e030d54672a0ae6 FOREIGN KEY (map_state_id) REFERENCES map_state (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e030d54672a0ae6 ON visitor_logs (map_state_id)');
    }
}
