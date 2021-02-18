<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210217102002 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room ADD creation_user_id INT NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD is_booked TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B876F01FE FOREIGN KEY (creation_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_729F519B876F01FE ON room (creation_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B876F01FE');
        $this->addSql('DROP INDEX IDX_729F519B876F01FE ON room');
        $this->addSql('ALTER TABLE room DROP creation_user_id, DROP city, DROP is_booked');
    }
}
