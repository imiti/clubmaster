<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120417072156 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE club_match_league_table (id INT AUTO_INCREMENT NOT NULL, league_id INT DEFAULT NULL, team_id INT DEFAULT NULL, played INT NOT NULL, win INT NOT NULL, loss INT NOT NULL, point INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2AF0A8FE58AFC4DE (league_id), INDEX IDX_2AF0A8FE296CD8AE (team_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE club_match_league_table ADD CONSTRAINT FK_2AF0A8FE58AFC4DE FOREIGN KEY (league_id) REFERENCES club_match_league(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_match_league_table ADD CONSTRAINT FK_2AF0A8FE296CD8AE FOREIGN KEY (team_id) REFERENCES club_match_team(id) ON DELETE CASCADE");
        $this->addSql("DROP TABLE club_match_match_league_table");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE club_match_match_league_table (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, league_id INT DEFAULT NULL, played INT NOT NULL, win INT NOT NULL, loss INT NOT NULL, point INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E3F85A3E58AFC4DE (league_id), INDEX IDX_E3F85A3E296CD8AE (team_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE club_match_match_league_table ADD CONSTRAINT FK_E3F85A3E296CD8AE FOREIGN KEY (team_id) REFERENCES club_match_team(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_match_match_league_table ADD CONSTRAINT FK_E3F85A3E58AFC4DE FOREIGN KEY (league_id) REFERENCES club_match_league(id) ON DELETE CASCADE");
        $this->addSql("DROP TABLE club_match_league_table");
    }
}