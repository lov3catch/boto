<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200217192819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE moderator_start_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_member_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_block_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_referral_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_setting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_owner_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE moderator_start (id INT NOT NULL, bot_id INT NOT NULL, user_id INT NOT NULL, is_superuser BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_member (id INT NOT NULL, member_id INT NOT NULL, member_is_bot BOOLEAN NOT NULL, member_first_name VARCHAR(255) NOT NULL, member_username VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_block (id INT NOT NULL, group_id BIGINT NOT NULL, user_id INT NOT NULL, admin_id INT NOT NULL, strategy VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_referral (id INT NOT NULL, group_id BIGINT NOT NULL, user_id INT NOT NULL, referral_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_setting (id INT NOT NULL, is_default BOOLEAN NOT NULL, max_message_words_count INT NOT NULL, max_message_chars_count INT NOT NULL, holdtime INT NOT NULL, max_daily_message_count INT NOT NULL, min_referrals_count INT NOT NULL, group_id BIGINT DEFAULT NULL, allow_link BOOLEAN NOT NULL, greeting_message TEXT DEFAULT NULL, greeting_buttons TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_group (id INT NOT NULL, group_id BIGINT NOT NULL, group_title VARCHAR(255) NOT NULL, group_username VARCHAR(255) NOT NULL, group_type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_owner (id INT NOT NULL, user_id INT NOT NULL, group_id BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE moderator_start_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_member_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_block_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_referral_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_setting_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_owner_id_seq CASCADE');
        $this->addSql('DROP TABLE moderator_start');
        $this->addSql('DROP TABLE moderator_member');
        $this->addSql('DROP TABLE moderator_block');
        $this->addSql('DROP TABLE moderator_referral');
        $this->addSql('DROP TABLE moderator_setting');
        $this->addSql('DROP TABLE moderator_group');
        $this->addSql('DROP TABLE moderator_owner');
    }
}
