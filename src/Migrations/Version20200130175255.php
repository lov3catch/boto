<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200130175255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE m_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_blocks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE channel_activity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_group_owners_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_ban_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_setting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_partners_program_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE m (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_blocks (id INT NOT NULL, group_id BIGINT NOT NULL, user_id BIGINT NOT NULL, strategy VARCHAR(255) NOT NULL, admin_id BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE "222"');
        $this->addSql('ALTER TABLE element ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER name SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER description SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER status SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER url SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER platform_id SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER type_id SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER group_id SET DEFAULT 0');
        $this->addSql('ALTER TABLE element ALTER group_id SET NOT NULL');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39C54C8C93 FOREIGN KEY (type_id) REFERENCES element_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_41405E39FFE6496F ON element (platform_id)');
        $this->addSql('CREATE INDEX IDX_41405E39C54C8C93 ON element (type_id)');
        $this->addSql('ALTER TABLE element ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE element_type ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE element_type ALTER name SET NOT NULL');
        $this->addSql('ALTER TABLE element_type ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE channel_activity ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE channel_activity ALTER channel_id SET NOT NULL');
        $this->addSql('ALTER TABLE channel_activity ALTER handler_name SET NOT NULL');
        $this->addSql('ALTER TABLE channel_activity ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE channel_activity ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER group_id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER partner_id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER is_active SET DEFAULT \'true\'');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER is_active SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_group_owners ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE platform ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE platform ALTER name SET NOT NULL');
        $this->addSql('ALTER TABLE platform ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE channel ALTER channel_id SET NOT NULL');
        $this->addSql('ALTER TABLE channel ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE channel ALTER updated_at SET NOT NULL');
        $this->addSql('ALTER TABLE channel ALTER handler_name SET NOT NULL');
        $this->addSql('ALTER TABLE channel ADD PRIMARY KEY (channel_id, handler_name)');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER admin_id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER user_id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER group_id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER expired_at SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE moderator_setting DROP last_greeting_id');
        $this->addSql('ALTER TABLE moderator_setting ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER is_default SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER max_words_count SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER max_chars_count SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER holdtime SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER max_daily_messages_count SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER min_referrals_count SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER allow_link SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER greeting DROP DEFAULT');
        $this->addSql('ALTER TABLE moderator_setting ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER group_id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER partner_id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER referral_id SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE moderator_partners_program ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE m_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_blocks_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE channel_activity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_group_owners_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_ban_list_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_setting_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_partners_program_id_seq CASCADE');
        $this->addSql('CREATE TABLE "222" (id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, status BOOLEAN DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, platform_id INT DEFAULT NULL, type_id INT DEFAULT NULL, group_id BIGINT DEFAULT NULL)');
        $this->addSql('DROP TABLE m');
        $this->addSql('DROP TABLE moderator_blocks');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE element_type ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE element_type ALTER name DROP NOT NULL');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER admin_id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER group_id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER expired_at DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_ban_list ALTER created_at DROP NOT NULL');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER group_id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER partner_id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER is_active DROP DEFAULT');
        $this->addSql('ALTER TABLE moderator_group_owners ALTER is_active DROP NOT NULL');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER group_id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER partner_id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER referral_id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE moderator_partners_program ALTER created_at DROP NOT NULL');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE platform ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE platform ALTER name DROP NOT NULL');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE moderator_setting ADD last_greeting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER is_default DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER max_words_count DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER max_chars_count DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER holdtime DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER max_daily_messages_count DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER min_referrals_count DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER allow_link DROP NOT NULL');
        $this->addSql('ALTER TABLE moderator_setting ALTER greeting SET DEFAULT \'\'');
        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E39FFE6496F');
        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E39C54C8C93');
        $this->addSql('DROP INDEX IDX_41405E39FFE6496F');
        $this->addSql('DROP INDEX IDX_41405E39C54C8C93');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE element ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER platform_id DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER type_id DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER name DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER url DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER group_id DROP DEFAULT');
        $this->addSql('ALTER TABLE element ALTER group_id DROP NOT NULL');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE channel ALTER channel_id DROP NOT NULL');
        $this->addSql('ALTER TABLE channel ALTER handler_name DROP NOT NULL');
        $this->addSql('ALTER TABLE channel ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE channel ALTER updated_at DROP NOT NULL');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE channel_activity ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE channel_activity ALTER channel_id DROP NOT NULL');
        $this->addSql('ALTER TABLE channel_activity ALTER handler_name DROP NOT NULL');
        $this->addSql('ALTER TABLE channel_activity ALTER created_at DROP NOT NULL');
    }
}
