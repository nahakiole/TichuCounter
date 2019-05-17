<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516122118 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, player_one_team_a_id INT NOT NULL, player_two_team_a_id INT NOT NULL, player_one_team_b_id INT NOT NULL, player_two_team_b_id INT DEFAULT NULL, INDEX IDX_232B318C57DEDE79 (player_one_team_a_id), INDEX IDX_232B318C770E48E9 (player_two_team_a_id), INDEX IDX_232B318C456B7197 (player_one_team_b_id), INDEX IDX_232B318C65BBE707 (player_two_team_b_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE round (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, points_team_a INT NOT NULL, points_team_b INT NOT NULL, finish_time DATETIME NOT NULL, INDEX IDX_C5EEEA34E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C57DEDE79 FOREIGN KEY (player_one_team_a_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C770E48E9 FOREIGN KEY (player_two_team_a_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C456B7197 FOREIGN KEY (player_one_team_b_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C65BBE707 FOREIGN KEY (player_two_team_b_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA34E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE round DROP FOREIGN KEY FK_C5EEEA34E48FD905');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C57DEDE79');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C770E48E9');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C456B7197');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C65BBE707');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE round');
        $this->addSql('DROP TABLE player');
    }
}
