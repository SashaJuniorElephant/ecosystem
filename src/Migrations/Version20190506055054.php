<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506055054 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE history_logs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE points_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE units_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE visitor_logs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE history_logs (id INT NOT NULL, map_id INT NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FBBAB62053C55F64 ON history_logs (map_id)');
        $this->addSql('CREATE TABLE points (id INT NOT NULL, game_id INT NOT NULL, x INT NOT NULL, y INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_27BA8E29E48FD905 ON points (game_id)');
        $this->addSql('CREATE TABLE units (id INT NOT NULL, point_id INT NOT NULL, map_state_id INT NOT NULL, type INT NOT NULL, name VARCHAR(255) NOT NULL, power INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E9B07449C028CEA2 ON units (point_id)');
        $this->addSql('CREATE INDEX IDX_E9B07449672A0AE6 ON units (map_state_id)');
        $this->addSql('CREATE TABLE visitor_logs (id INT NOT NULL, map_state_id INT NOT NULL, unit_id INT NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E030D54672A0AE6 ON visitor_logs (map_state_id)');
        $this->addSql('CREATE INDEX IDX_E030D54F8BD700D ON visitor_logs (unit_id)');
        $this->addSql('ALTER TABLE history_logs ADD CONSTRAINT FK_FBBAB62053C55F64 FOREIGN KEY (map_id) REFERENCES map_state (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE points ADD CONSTRAINT FK_27BA8E29E48FD905 FOREIGN KEY (game_id) REFERENCES games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE units ADD CONSTRAINT FK_E9B07449C028CEA2 FOREIGN KEY (point_id) REFERENCES points (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE units ADD CONSTRAINT FK_E9B07449672A0AE6 FOREIGN KEY (map_state_id) REFERENCES map_state (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visitor_logs ADD CONSTRAINT FK_E030D54672A0AE6 FOREIGN KEY (map_state_id) REFERENCES map_state (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visitor_logs ADD CONSTRAINT FK_E030D54F8BD700D FOREIGN KEY (unit_id) REFERENCES units (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE units DROP CONSTRAINT FK_E9B07449C028CEA2');
        $this->addSql('ALTER TABLE visitor_logs DROP CONSTRAINT FK_E030D54F8BD700D');
        $this->addSql('DROP SEQUENCE history_logs_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE points_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE units_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE visitor_logs_id_seq CASCADE');
        $this->addSql('DROP TABLE history_logs');
        $this->addSql('DROP TABLE points');
        $this->addSql('DROP TABLE units');
        $this->addSql('DROP TABLE visitor_logs');
    }
}
