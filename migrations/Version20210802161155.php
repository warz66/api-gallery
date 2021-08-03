<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210802161155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tableau (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, year INT DEFAULT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, technique VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD tableau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB062D5BC FOREIGN KEY (tableau_id) REFERENCES tableau (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C53D045FB062D5BC ON image (tableau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FB062D5BC');
        $this->addSql('DROP TABLE tableau');
        $this->addSql('DROP INDEX UNIQ_C53D045FB062D5BC ON image');
        $this->addSql('ALTER TABLE image DROP tableau_id');
    }
}
