<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180630110220 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_232B318CA0F35D66');
        $this->addSql('DROP INDEX UNIQ_232B318C42C04473');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game AS SELECT id, current_player_id, current_question_id, reference, max_points, current_turn FROM game');
        $this->addSql('DROP TABLE game');
        $this->addSql('CREATE TABLE game (id INTEGER NOT NULL, current_player_id INTEGER DEFAULT NULL, current_question_id INTEGER DEFAULT NULL, reference VARCHAR(36) NOT NULL COLLATE BINARY, max_points INTEGER NOT NULL, current_turn INTEGER DEFAULT NULL, state VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_232B318C42C04473 FOREIGN KEY (current_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_232B318CA0F35D66 FOREIGN KEY (current_question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game (id, current_player_id, current_question_id, reference, max_points, current_turn) SELECT id, current_player_id, current_question_id, reference, max_points, current_turn FROM __temp__game');
        $this->addSql('DROP TABLE __temp__game');
        $this->addSql('CREATE INDEX IDX_232B318CA0F35D66 ON game (current_question_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C42C04473 ON game (current_player_id)');
        $this->addSql('DROP INDEX IDX_1DB3B668E48FD905');
        $this->addSql('DROP INDEX IDX_1DB3B6681E27F6BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_question AS SELECT game_id, question_id FROM game_question');
        $this->addSql('DROP TABLE game_question');
        $this->addSql('CREATE TABLE game_question (game_id INTEGER NOT NULL, question_id INTEGER NOT NULL, PRIMARY KEY(game_id, question_id), CONSTRAINT FK_1DB3B668E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1DB3B6681E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_question (game_id, question_id) SELECT game_id, question_id FROM __temp__game_question');
        $this->addSql('DROP TABLE __temp__game_question');
        $this->addSql('CREATE INDEX IDX_1DB3B668E48FD905 ON game_question (game_id)');
        $this->addSql('CREATE INDEX IDX_1DB3B6681E27F6BF ON game_question (question_id)');
        $this->addSql('DROP INDEX IDX_98197A65E48FD905');
        $this->addSql('CREATE TEMPORARY TABLE __temp__player AS SELECT id, game_id, name, score FROM player');
        $this->addSql('DROP TABLE player');
        $this->addSql('CREATE TABLE player (id INTEGER NOT NULL, game_id INTEGER NOT NULL, name VARCHAR(32) NOT NULL COLLATE BINARY, score INTEGER DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_98197A65E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO player (id, game_id, name, score) SELECT id, game_id, name, score FROM __temp__player');
        $this->addSql('DROP TABLE __temp__player');
        $this->addSql('CREATE INDEX IDX_98197A65E48FD905 ON player (game_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_232B318C42C04473');
        $this->addSql('DROP INDEX IDX_232B318CA0F35D66');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game AS SELECT id, current_player_id, current_question_id, reference, max_points, current_turn FROM game');
        $this->addSql('DROP TABLE game');
        $this->addSql('CREATE TABLE game (id INTEGER NOT NULL, current_player_id INTEGER DEFAULT NULL, current_question_id INTEGER DEFAULT NULL, reference VARCHAR(36) NOT NULL, max_points INTEGER NOT NULL, current_turn INTEGER DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO game (id, current_player_id, current_question_id, reference, max_points, current_turn) SELECT id, current_player_id, current_question_id, reference, max_points, current_turn FROM __temp__game');
        $this->addSql('DROP TABLE __temp__game');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C42C04473 ON game (current_player_id)');
        $this->addSql('CREATE INDEX IDX_232B318CA0F35D66 ON game (current_question_id)');
        $this->addSql('DROP INDEX IDX_1DB3B668E48FD905');
        $this->addSql('DROP INDEX IDX_1DB3B6681E27F6BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_question AS SELECT game_id, question_id FROM game_question');
        $this->addSql('DROP TABLE game_question');
        $this->addSql('CREATE TABLE game_question (game_id INTEGER NOT NULL, question_id INTEGER NOT NULL, PRIMARY KEY(game_id, question_id))');
        $this->addSql('INSERT INTO game_question (game_id, question_id) SELECT game_id, question_id FROM __temp__game_question');
        $this->addSql('DROP TABLE __temp__game_question');
        $this->addSql('CREATE INDEX IDX_1DB3B668E48FD905 ON game_question (game_id)');
        $this->addSql('CREATE INDEX IDX_1DB3B6681E27F6BF ON game_question (question_id)');
        $this->addSql('DROP INDEX IDX_98197A65E48FD905');
        $this->addSql('CREATE TEMPORARY TABLE __temp__player AS SELECT id, game_id, name, score FROM player');
        $this->addSql('DROP TABLE player');
        $this->addSql('CREATE TABLE player (id INTEGER NOT NULL, game_id INTEGER NOT NULL, name VARCHAR(32) NOT NULL, score INTEGER DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO player (id, game_id, name, score) SELECT id, game_id, name, score FROM __temp__player');
        $this->addSql('DROP TABLE __temp__player');
        $this->addSql('CREATE INDEX IDX_98197A65E48FD905 ON player (game_id)');
    }
}
