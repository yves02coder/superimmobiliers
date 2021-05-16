<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512113810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP first_image, CHANGE image_title image_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE immobilier DROP FOREIGN KEY FK_142D24D2D44F05E5');
        $this->addSql('DROP INDEX IDX_142D24D2D44F05E5 ON immobilier');
        $this->addSql('ALTER TABLE immobilier DROP images_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD first_image TINYINT(1) NOT NULL, CHANGE image_name image_title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE immobilier ADD images_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE immobilier ADD CONSTRAINT FK_142D24D2D44F05E5 FOREIGN KEY (images_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_142D24D2D44F05E5 ON immobilier (images_id)');
    }
}
