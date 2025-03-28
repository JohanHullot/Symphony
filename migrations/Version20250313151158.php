<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313151158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_user (event_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(event_id, user_id), CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_92589AE271F7E88B ON event_user (event_id)');
        $this->addSql('CREATE INDEX IDX_92589AE2A76ED395 ON event_user (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, artist_id, name, date FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, artist_id INTEGER NOT NULL, creator_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, CONSTRAINT FK_3BAE0AA7B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3BAE0AA761220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, artist_id, name, date) SELECT id, artist_id, name, date FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7B7970CF8 ON event (artist_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA761220EA6 ON event (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, artist_id, name, date FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, artist_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, CONSTRAINT FK_3BAE0AA7B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, artist_id, name, date) SELECT id, artist_id, name, date FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7B7970CF8 ON event (artist_id)');
    }
}
