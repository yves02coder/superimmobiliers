<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512095344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FFFE49257');
        $this->addSql('DROP INDEX IDX_C53D045FFFE49257 ON image');
        $this->addSql('ALTER TABLE image DROP imagesimmobilier_id');
        $this->addSql('ALTER TABLE immobilier ADD images_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE immobilier ADD CONSTRAINT FK_142D24D2D44F05E5 FOREIGN KEY (images_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_142D24D2D44F05E5 ON immobilier (images_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD imagesimmobilier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FFFE49257 FOREIGN KEY (imagesimmobilier_id) REFERENCES immobilier (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FFFE49257 ON image (imagesimmobilier_id)');
        $this->addSql('ALTER TABLE immobilier DROP FOREIGN KEY FK_142D24D2D44F05E5');
        $this->addSql('DROP INDEX IDX_142D24D2D44F05E5 ON immobilier');
        $this->addSql('ALTER TABLE immobilier DROP images_id');
    }
}
