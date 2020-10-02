<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201001115824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD shopping_card_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993985446D752 FOREIGN KEY (shopping_card_id) REFERENCES shopping_card (id)');
        $this->addSql('CREATE INDEX IDX_F52993985446D752 ON `order` (shopping_card_id)');
        $this->addSql('ALTER TABLE shopping_card DROP items_on_card, DROP purchased_items');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993985446D752');
        $this->addSql('DROP INDEX IDX_F52993985446D752 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP shopping_card_id');
        $this->addSql('ALTER TABLE shopping_card ADD items_on_card LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', ADD purchased_items LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
