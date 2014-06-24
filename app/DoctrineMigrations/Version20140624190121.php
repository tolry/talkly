<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140624190121 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE speaker (topic_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7B85DB611F55203D (topic_id), INDEX IDX_7B85DB61A76ED395 (user_id), PRIMARY KEY(topic_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE speaker ADD CONSTRAINT FK_7B85DB611F55203D FOREIGN KEY (topic_id) REFERENCES Topic (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE speaker ADD CONSTRAINT FK_7B85DB61A76ED395 FOREIGN KEY (user_id) REFERENCES UserProfil (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE speaker");
    }
}
