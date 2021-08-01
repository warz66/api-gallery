<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210801005804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE galerie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, cover_image VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, order_by TINYINT(1) NOT NULL, statut TINYINT(1) NOT NULL, trash TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, galerie_id INT DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_C53D045F825396CB (galerie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F825396CB FOREIGN KEY (galerie_id) REFERENCES galerie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F825396CB');
        $this->addSql('DROP TABLE galerie');
        $this->addSql('DROP TABLE image');
    }
}
