<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140630200417 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE vote (topic_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5A1085641F55203D (topic_id), INDEX IDX_5A108564A76ED395 (user_id), PRIMARY KEY(topic_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE vote ADD CONSTRAINT FK_5A1085641F55203D FOREIGN KEY (topic_id) REFERENCES Topic (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE vote ADD CONSTRAINT FK_5A108564A76ED395 FOREIGN KEY (user_id) REFERENCES UserProfil (id) ON DELETE CASCADE");

        $this->addSql("INSERT INTO vote (topic_id, user_id)
            SELECT topic_id, user_id FROM votes");

        $this->addSql("DROP TABLE votes");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE votes (topic_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_518B7ACF1F55203D (topic_id), INDEX IDX_518B7ACFA76ED395 (user_id), PRIMARY KEY(topic_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFA76ED395 FOREIGN KEY (user_id) REFERENCES UserProfil (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF1F55203D FOREIGN KEY (topic_id) REFERENCES Topic (id) ON DELETE CASCADE");
        $this->addSql("DROP TABLE vote");
    }
}
