<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190525065456 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE "222" DROP CONSTRAINT fk_41405e394d18fad3');
        $this->addSql('ALTER TABLE "222" DROP CONSTRAINT fk_41405e39714819a0');
        $this->addSql('DROP INDEX idx_41405e394d18fad3');
        $this->addSql('DROP INDEX idx_41405e39714819a0');
        $this->addSql('ALTER TABLE "222" ADD platform_id INT NOT NULL');
        $this->addSql('ALTER TABLE "222" ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE "222" DROP platform_id_id');
        $this->addSql('ALTER TABLE "222" DROP type_id_id');
        $this->addSql('ALTER TABLE "222" ADD CONSTRAINT FK_41405E39FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "222" ADD CONSTRAINT FK_41405E39C54C8C93 FOREIGN KEY (type_id) REFERENCES element_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_41405E39FFE6496F ON element (platform_id)');
        $this->addSql('CREATE INDEX IDX_41405E39C54C8C93 ON element (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "222" DROP CONSTRAINT FK_41405E39FFE6496F');
        $this->addSql('ALTER TABLE "222" DROP CONSTRAINT FK_41405E39C54C8C93');
        $this->addSql('DROP INDEX IDX_41405E39FFE6496F');
        $this->addSql('DROP INDEX IDX_41405E39C54C8C93');
        $this->addSql('ALTER TABLE "222" ADD platform_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE "222" ADD type_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE "222" DROP platform_id');
        $this->addSql('ALTER TABLE "222" DROP type_id');
        $this->addSql('ALTER TABLE "222" ADD CONSTRAINT fk_41405e394d18fad3 FOREIGN KEY (platform_id_id) REFERENCES platform (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "222" ADD CONSTRAINT fk_41405e39714819a0 FOREIGN KEY (type_id_id) REFERENCES element_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_41405e394d18fad3 ON element (platform_id_id)');
        $this->addSql('CREATE INDEX idx_41405e39714819a0 ON element (type_id_id)');
    }
}
