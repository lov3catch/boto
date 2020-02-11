<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200119141730 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE moderator_setting ALTER group_id TYPE BIGINT');
        $this->addSql('ALTER TABLE moderator_setting ALTER group_id DROP DEFAULT');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER group_id TYPE BIGINT');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER group_id DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE moderator_setting ALTER group_id TYPE INT');
        $this->addSql('ALTER TABLE moderator_setting ALTER group_id DROP DEFAULT');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER group_id TYPE INT');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER group_id DROP DEFAULT');
    }
}
