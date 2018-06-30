<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180630084020 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE game (id INTEGER NOT NULL, reference VARCHAR(36) NOT NULL, max_points INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE game_question (game_id INTEGER NOT NULL, question_id INTEGER NOT NULL, PRIMARY KEY(game_id, question_id))');
        $this->addSql('CREATE INDEX IDX_1DB3B668E48FD905 ON game_question (game_id)');
        $this->addSql('CREATE INDEX IDX_1DB3B6681E27F6BF ON game_question (question_id)');
        $this->addSql('CREATE TABLE player (id INTEGER NOT NULL, game_id INTEGER NOT NULL, name VARCHAR(32) NOT NULL, score INTEGER DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98197A65E48FD905 ON player (game_id)');
        $this->addSql('CREATE TABLE question (id INTEGER NOT NULL, question CLOB NOT NULL, answer BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_question');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE question');
    }
}
