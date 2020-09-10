<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200820115102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, is_correct TINYINT(1) DEFAULT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, content LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, question_group_id INT DEFAULT NULL, next_id INT DEFAULT NULL, previos_id INT DEFAULT NULL, scenario_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, is_multiable_choice TINYINT(1) DEFAULT NULL, INDEX IDX_B6F7494E9D5C694B (question_group_id), UNIQUE INDEX UNIQ_B6F7494EAA23F6C8 (next_id), UNIQUE INDEX UNIQ_B6F7494E836E02D (previos_id), INDEX IDX_B6F7494EE04E49DF (scenario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scenario (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, fach VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, total_points INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E9D5C694B FOREIGN KEY (question_group_id) REFERENCES question_group (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EAA23F6C8 FOREIGN KEY (next_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E836E02D FOREIGN KEY (previos_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EE04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EAA23F6C8');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E836E02D');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9D5C694B');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EE04E49DF');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_group');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('DROP TABLE user');
    }
}
