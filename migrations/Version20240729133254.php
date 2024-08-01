<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729133254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_entity ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE bucket_entity ADD CONSTRAINT FK_699AF7D912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_699AF7D912469DE2 ON bucket_entity (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_entity DROP FOREIGN KEY FK_699AF7D912469DE2');
        $this->addSql('DROP INDEX IDX_699AF7D912469DE2 ON bucket_entity');
        $this->addSql('ALTER TABLE bucket_entity DROP category_id');
    }
}
