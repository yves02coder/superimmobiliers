<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512113938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD immobilier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F5C7B99A9 FOREIGN KEY (immobilier_id) REFERENCES immobilier (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F5C7B99A9 ON image (immobilier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F5C7B99A9');
        $this->addSql('DROP INDEX IDX_C53D045F5C7B99A9 ON image');
        $this->addSql('ALTER TABLE image DROP immobilier_id');
    }
}
