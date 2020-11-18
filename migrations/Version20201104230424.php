<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201104230424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, zip_code VARCHAR(255) NOT NULL, city VARCHAR(255) DEFAULT NULL, street_name VARCHAR(255) DEFAULT NULL, house_number VARCHAR(255) DEFAULT NULL, is_main TINYINT(1) DEFAULT NULL, INDEX IDX_D4E6F81A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, is_correct TINYINT(1) DEFAULT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE link (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, private TINYINT(1) DEFAULT NULL, favorite TINYINT(1) DEFAULT NULL, rate DOUBLE PRECISION DEFAULT NULL, host_name SMALLINT DEFAULT NULL, INDEX IDX_36AC99F1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, content LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_order (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, shopping_card_id INT DEFAULT NULL, quantity SMALLINT DEFAULT NULL, order_date DATE DEFAULT NULL, state SMALLINT DEFAULT NULL, UNIQUE INDEX UNIQ_5475E8C44584665A (product_id), INDEX IDX_5475E8C45446D752 (shopping_card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, quantity SMALLINT DEFAULT NULL, UNIQUE INDEX UNIQ_C890CED44584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, question_group_id INT DEFAULT NULL, next_id INT DEFAULT NULL, previos_id INT DEFAULT NULL, scenario_id INT DEFAULT NULL, title LONGTEXT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, is_multiple_choice TINYINT(1) DEFAULT NULL, points SMALLINT DEFAULT NULL, currect_answer VARCHAR(10) DEFAULT NULL, answer_description LONGTEXT DEFAULT NULL, INDEX IDX_B6F7494E9D5C694B (question_group_id), UNIQUE INDEX UNIQ_B6F7494EAA23F6C8 (next_id), UNIQUE INDEX UNIQ_B6F7494E836E02D (previos_id), INDEX IDX_B6F7494EE04E49DF (scenario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, partner_id INT DEFAULT NULL, status SMALLINT DEFAULT NULL, INDEX IDX_62894749A76ED395 (user_id), INDEX IDX_628947499393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scenario (id INT AUTO_INCREMENT NOT NULL, question_group_id INT DEFAULT NULL, title LONGTEXT DEFAULT NULL, fach VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, points INT DEFAULT NULL, INDEX IDX_3E45C8D89D5C694B (question_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_card (id INT AUTO_INCREMENT NOT NULL, sum DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, shopping_card_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, gender SMALLINT DEFAULT NULL, givenname VARCHAR(255) DEFAULT NULL, surname VARCHAR(255) DEFAULT NULL, birthdate DATE DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6495446D752 (shopping_card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_36AC99F1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C44584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C45446D752 FOREIGN KEY (shopping_card_id) REFERENCES shopping_card (id)');
        $this->addSql('ALTER TABLE purchase_product ADD CONSTRAINT FK_C890CED44584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E9D5C694B FOREIGN KEY (question_group_id) REFERENCES question_group (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EAA23F6C8 FOREIGN KEY (next_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E836E02D FOREIGN KEY (previos_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EE04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_628947499393F8FE FOREIGN KEY (partner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE scenario ADD CONSTRAINT FK_3E45C8D89D5C694B FOREIGN KEY (question_group_id) REFERENCES question_group (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495446D752 FOREIGN KEY (shopping_card_id) REFERENCES shopping_card (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C44584665A');
        $this->addSql('ALTER TABLE purchase_product DROP FOREIGN KEY FK_C890CED44584665A');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EAA23F6C8');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E836E02D');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9D5C694B');
        $this->addSql('ALTER TABLE scenario DROP FOREIGN KEY FK_3E45C8D89D5C694B');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EE04E49DF');
        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C45446D752');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495446D752');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_36AC99F1A76ED395');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749A76ED395');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_628947499393F8FE');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE link');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_order');
        $this->addSql('DROP TABLE purchase_product');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_group');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('DROP TABLE shopping_card');
        $this->addSql('DROP TABLE user');
    }
}
