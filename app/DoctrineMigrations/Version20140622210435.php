<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140622210435 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE Topic ADD createdBy_id INT DEFAULT NULL, ADD lectureUser_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE Topic ADD CONSTRAINT FK_5C81F11F3174800F FOREIGN KEY (createdBy_id) REFERENCES User (id)");
        $this->addSql("ALTER TABLE Topic ADD CONSTRAINT FK_5C81F11FD6F9AF20 FOREIGN KEY (lectureUser_id) REFERENCES User (id)");
        $this->addSql("CREATE INDEX IDX_5C81F11F3174800F ON Topic (createdBy_id)");
        $this->addSql("CREATE INDEX IDX_5C81F11FD6F9AF20 ON Topic (lectureUser_id)");
        $this->addSql("ALTER TABLE Vote ADD voter_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE Vote ADD CONSTRAINT FK_FA222A5AEBB4B8AD FOREIGN KEY (voter_id) REFERENCES User (id)");
        $this->addSql("CREATE INDEX IDX_FA222A5AEBB4B8AD ON Vote (voter_id)");
        $this->addSql("ALTER TABLE Comment ADD createdBy_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF03174800F FOREIGN KEY (createdBy_id) REFERENCES User (id)");
        $this->addSql("CREATE INDEX IDX_5BC96BF03174800F ON Comment (createdBy_id)");

        $this->addSql("INSERT INTO User (username) SELECT * FROM ((SELECT createdBy AS username FROM Comment) UNION (SELECT voter AS username FROM Vote) UNION (SELECT createdBy AS username FROM Topic) UNION (SELECT lectureUser AS username FROM Topic)) AS t WHERE username IS NOT NULL");

        $this->addSql("UPDATE Comment SET createdBy_id = (SELECT id FROM User WHERE username = createdBy)");
        $this->addSql("UPDATE Vote SET voter_id = (SELECT id FROM User WHERE username = voter)");
        $this->addSql("UPDATE Topic SET createdBy_id = (SELECT id FROM User WHERE username = createdBy)");
        $this->addSql("UPDATE Topic SET lectureUser_id = (SELECT id FROM User WHERE username = lectureUser)");


        $this->addSql("ALTER TABLE Comment DROP createdBy");
        $this->addSql("ALTER TABLE Vote DROP voter");
        $this->addSql("ALTER TABLE Topic DROP createdBy, DROP lectureUser");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE Topic DROP FOREIGN KEY FK_5C81F11F3174800F");
        $this->addSql("ALTER TABLE Topic DROP FOREIGN KEY FK_5C81F11FD6F9AF20");
        $this->addSql("ALTER TABLE Vote DROP FOREIGN KEY FK_FA222A5AEBB4B8AD");
        $this->addSql("ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF03174800F");
        $this->addSql("DROP TABLE User");
        $this->addSql("DROP INDEX IDX_5BC96BF03174800F ON Comment");
        $this->addSql("ALTER TABLE Comment ADD createdBy VARCHAR(255) NOT NULL, DROP createdBy_id");
        $this->addSql("DROP INDEX IDX_5C81F11F3174800F ON Topic");
        $this->addSql("DROP INDEX IDX_5C81F11FD6F9AF20 ON Topic");
        $this->addSql("ALTER TABLE Topic ADD createdBy VARCHAR(255) DEFAULT NULL, ADD lectureUser VARCHAR(255) DEFAULT NULL, DROP createdBy_id, DROP lectureUser_id");
        $this->addSql("DROP INDEX IDX_FA222A5AEBB4B8AD ON Vote");
        $this->addSql("ALTER TABLE Vote ADD voter VARCHAR(255) DEFAULT NULL, DROP voter_id");
    }
}
