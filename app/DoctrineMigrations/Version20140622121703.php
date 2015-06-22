<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140622121703 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE Topic (id INT AUTO_INCREMENT NOT NULL, createdBy VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, lectureNote LONGTEXT DEFAULT NULL, lectureUser VARCHAR(255) DEFAULT NULL, lectureDate DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Vote (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, voter VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, INDEX IDX_FA222A5A1F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Comment (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, createdBy VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, commentText LONGTEXT NOT NULL, INDEX IDX_5BC96BF01F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE Vote ADD CONSTRAINT FK_FA222A5A1F55203D FOREIGN KEY (topic_id) REFERENCES Topic (id)");
        $this->addSql("ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF01F55203D FOREIGN KEY (topic_id) REFERENCES Topic (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Vote DROP FOREIGN KEY FK_FA222A5A1F55203D");
        $this->addSql("ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF01F55203D");
        $this->addSql("DROP TABLE Topic");
        $this->addSql("DROP TABLE Vote");
        $this->addSql("DROP TABLE Comment");
    }
}
