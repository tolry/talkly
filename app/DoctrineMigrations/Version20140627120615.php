<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140627120615 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Topic DROP FOREIGN KEY FK_5C81F11FD6F9AF20");
        $this->addSql("DROP INDEX IDX_5C81F11FD6F9AF20 ON Topic");
        $this->addSql("ALTER TABLE Topic DROP lectureUser_id");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Topic ADD lectureUser_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE Topic ADD CONSTRAINT FK_5C81F11FD6F9AF20 FOREIGN KEY (lectureUser_id) REFERENCES UserProfil (id)");
        $this->addSql("CREATE INDEX IDX_5C81F11FD6F9AF20 ON Topic (lectureUser_id)");
    }
}
