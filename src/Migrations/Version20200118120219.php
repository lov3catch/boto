<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200118120219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE moderator_setting ADD group_id INT DEFAULT NULL');
//        $this->addSql('ALTER TABLE moderator_setting ALTER max_words_count DROP DEFAULT');
//        $this->addSql('ALTER TABLE moderator_setting ALTER max_chars_count DROP DEFAULT');
//        $this->addSql('ALTER TABLE moderator_setting ALTER holdtime DROP DEFAULT');
//        $this->addSql('ALTER TABLE moderator_setting ALTER max_daily_messages_count DROP DEFAULT');
//        $this->addSql('ALTER TABLE moderator_setting ALTER min_referrals_count DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE moderator_setting DROP group_id');
//        $this->addSql('ALTER TABLE moderator_setting ALTER max_words_count SET DEFAULT 15');
//        $this->addSql('ALTER TABLE moderator_setting ALTER max_chars_count SET DEFAULT 75');
//        $this->addSql('ALTER TABLE moderator_setting ALTER holdtime SET DEFAULT 24');
//        $this->addSql('ALTER TABLE moderator_setting ALTER max_daily_messages_count SET DEFAULT 1');
//        $this->addSql('ALTER TABLE moderator_setting ALTER min_referrals_count SET DEFAULT 1');
    }
}
