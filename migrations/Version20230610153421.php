<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230610153421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE division_game_score (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, score INT NOT NULL, best TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B5B20BA5296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, team_1_id INT NOT NULL, team_2_id INT NOT NULL, team_1_score INT NOT NULL, team_2_score INT NOT NULL, game_type VARCHAR(20) NOT NULL, INDEX IDX_232B318C2132A881 (team_1_id), INDEX IDX_232B318C3387076F (team_2_id), INDEX game_type (game_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, division VARCHAR(1) NOT NULL, INDEX division (division), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE division_game_score ADD CONSTRAINT FK_B5B20BA5296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C2132A881 FOREIGN KEY (team_1_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C3387076F FOREIGN KEY (team_2_id) REFERENCES team (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE division_game_score DROP FOREIGN KEY FK_B5B20BA5296CD8AE');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C2132A881');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C3387076F');
        $this->addSql('DROP TABLE division_game_score');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE team');
    }
}
