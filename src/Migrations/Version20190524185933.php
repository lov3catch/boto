<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190524185933 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE element_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE element_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE platform_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE element (id INT NOT NULL, platform_id_id INT NOT NULL, type_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_41405E394D18FAD3 ON element (platform_id_id)');
        $this->addSql('CREATE INDEX IDX_41405E39714819A0 ON element (type_id_id)');
        $this->addSql('CREATE TABLE element_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE platform (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "222" ADD CONSTRAINT FK_41405E394D18FAD3 FOREIGN KEY (platform_id_id) REFERENCES platform (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "222" ADD CONSTRAINT FK_41405E39714819A0 FOREIGN KEY (type_id_id) REFERENCES element_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "222" DROP CONSTRAINT FK_41405E39714819A0');
        $this->addSql('ALTER TABLE "222" DROP CONSTRAINT FK_41405E394D18FAD3');
        $this->addSql('DROP SEQUENCE element_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE element_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE platform_id_seq CASCADE');
        $this->addSql('DROP TABLE "222"');
        $this->addSql('DROP TABLE element_type');
        $this->addSql('DROP TABLE platform');
    }
}
