<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140629141817 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE votes (topic_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_518B7ACF1F55203D (topic_id), INDEX IDX_518B7ACFA76ED395 (user_id), PRIMARY KEY(topic_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF1F55203D FOREIGN KEY (topic_id) REFERENCES Topic (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFA76ED395 FOREIGN KEY (user_id) REFERENCES UserProfil (id) ON DELETE CASCADE");

        $this->addSql("INSERT INTO votes (topic_id, user_id)
            SELECT topic_id, voter_id FROM Vote");

        $this->addSql("DROP TABLE Vote");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE Vote (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, voter_id INT DEFAULT NULL, createdAt DATETIME DEFAULT NULL, INDEX IDX_FA222A5A1F55203D (topic_id), INDEX IDX_FA222A5AEBB4B8AD (voter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE Vote ADD CONSTRAINT FK_FA222A5A1F55203D FOREIGN KEY (topic_id) REFERENCES Topic (id)");
        $this->addSql("ALTER TABLE Vote ADD CONSTRAINT FK_FA222A5AEBB4B8AD FOREIGN KEY (voter_id) REFERENCES UserProfil (id)");

        $this->addSql("DROP TABLE votes");
    }
}
