<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240730123715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_entity ADD author_id INT DEFAULT NULL, DROP author');
        $this->addSql('ALTER TABLE bucket_entity ADD CONSTRAINT FK_699AF7D9F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_699AF7D9F675F31B ON bucket_entity (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_entity DROP FOREIGN KEY FK_699AF7D9F675F31B');
        $this->addSql('DROP INDEX IDX_699AF7D9F675F31B ON bucket_entity');
        $this->addSql('ALTER TABLE bucket_entity ADD author VARCHAR(50) NOT NULL, DROP author_id');
    }
}
