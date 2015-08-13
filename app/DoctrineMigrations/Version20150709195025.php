<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150709195025 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fun_group DROP FOREIGN KEY FK_A0734D36A76ED395');
        $this->addSql('ALTER TABLE fun_group ADD CONSTRAINT FK_A0734D36A76ED395 FOREIGN KEY (user_id) REFERENCES FunGroup (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fun_group DROP FOREIGN KEY FK_A0734D36A76ED395');
        $this->addSql('ALTER TABLE fun_group ADD CONSTRAINT FK_A0734D36A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
    }
}
