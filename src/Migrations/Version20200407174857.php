<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407174857 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE moderator_setting ADD sleep_from VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE moderator_setting ADD sleep_until VARCHAR(255) DEFAULT NULL');
//        $this->addSql('ALTER TABLE moderator_setting ALTER stop_words DROP DEFAULT');
//        $this->addSql('DROP INDEX "primary"');
//        $this->addSql('ALTER TABLE channel ADD language_code VARCHAR(5) DEFAULT NULL');
//        $this->addSql('ALTER TABLE channel DROP id');
//        $this->addSql('ALTER TABLE channel DROP token');
//        $this->addSql('ALTER TABLE channel ADD PRIMARY KEY (channel_id, handler_name)');
//        $this->addSql('DROP INDEX idx_41405e39714819a0');
//        $this->addSql('DROP INDEX idx_41405e394d18fad3');
//        $this->addSql('ALTER TABLE element ADD platform_id INT NOT NULL');
//        $this->addSql('ALTER TABLE element ADD type_id INT NOT NULL');
//        $this->addSql('ALTER TABLE element ADD group_id BIGINT DEFAULT 0 NOT NULL');
//        $this->addSql('ALTER TABLE element DROP platform_id_id');
//        $this->addSql('ALTER TABLE element DROP type_id_id');
//        $this->addSql('ALTER TABLE element RENAME COLUMN title TO url');
//        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
//        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39C54C8C93 FOREIGN KEY (type_id) REFERENCES element_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
//        $this->addSql('CREATE INDEX IDX_41405E39FFE6496F ON element (platform_id)');
//        $this->addSql('CREATE INDEX IDX_41405E39C54C8C93 ON element (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

//        $this->addSql('CREATE SCHEMA public');
//        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E39FFE6496F');
//        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E39C54C8C93');
//        $this->addSql('DROP INDEX IDX_41405E39FFE6496F');
//        $this->addSql('DROP INDEX IDX_41405E39C54C8C93');
//        $this->addSql('ALTER TABLE element ADD platform_id_id INT NOT NULL');
//        $this->addSql('ALTER TABLE element ADD type_id_id INT NOT NULL');
//        $this->addSql('ALTER TABLE element DROP platform_id');
//        $this->addSql('ALTER TABLE element DROP type_id');
//        $this->addSql('ALTER TABLE element DROP group_id');
//        $this->addSql('ALTER TABLE element RENAME COLUMN url TO title');
//        $this->addSql('CREATE INDEX idx_41405e39714819a0 ON element (type_id_id)');
//        $this->addSql('CREATE INDEX idx_41405e394d18fad3 ON element (platform_id_id)');
//        $this->addSql('DROP INDEX channel_pkey');
//        $this->addSql('ALTER TABLE channel ADD id INT NOT NULL');
//        $this->addSql('ALTER TABLE channel ADD token VARCHAR(255) NOT NULL');
//        $this->addSql('ALTER TABLE channel DROP language_code');
//        $this->addSql('ALTER TABLE channel ADD PRIMARY KEY (id)');
//        $this->addSql('ALTER TABLE moderator_setting DROP sleep_from');
        $this->addSql('ALTER TABLE moderator_setting DROP sleep_until');
//        $this->addSql('ALTER TABLE moderator_setting ALTER stop_words SET DEFAULT \'[]\'');
    }
}
