<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190622195208 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE channel_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE channel_channel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE channel ADD handler_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE channel DROP handler');
        $this->addSql('ALTER TABLE channel ALTER language_code DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE channel_channel_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE channel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE channel ADD handler VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE channel DROP handler_name');
        $this->addSql('ALTER TABLE channel ALTER language_code SET DEFAULT \'en\'');
    }
}
