<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240801103006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE association_bucket_user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association_bucket_user_user (association_bucket_user_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_18AB1AC231937DC1 (association_bucket_user_id), INDEX IDX_18AB1AC2A76ED395 (user_id), PRIMARY KEY(association_bucket_user_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association_bucket_user_bucket_entity (association_bucket_user_id INT NOT NULL, bucket_entity_id INT NOT NULL, INDEX IDX_76B457F631937DC1 (association_bucket_user_id), INDEX IDX_76B457F6AF3EC6C6 (bucket_entity_id), PRIMARY KEY(association_bucket_user_id, bucket_entity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bucket_entity (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, is_published TINYINT(1) NOT NULL, status VARCHAR(6) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_699AF7D912469DE2 (category_id), INDEX IDX_699AF7D9F675F31B (author_id), INDEX IDX_699AF7D9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(30) DEFAULT NULL, lastname VARCHAR(30) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE association_bucket_user_user ADD CONSTRAINT FK_18AB1AC231937DC1 FOREIGN KEY (association_bucket_user_id) REFERENCES association_bucket_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE association_bucket_user_user ADD CONSTRAINT FK_18AB1AC2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE association_bucket_user_bucket_entity ADD CONSTRAINT FK_76B457F631937DC1 FOREIGN KEY (association_bucket_user_id) REFERENCES association_bucket_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE association_bucket_user_bucket_entity ADD CONSTRAINT FK_76B457F6AF3EC6C6 FOREIGN KEY (bucket_entity_id) REFERENCES bucket_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bucket_entity ADD CONSTRAINT FK_699AF7D912469DE2 FOREIGN KEY (category_id) REFERENCES category_entity (id)');
        $this->addSql('ALTER TABLE bucket_entity ADD CONSTRAINT FK_699AF7D9F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bucket_entity ADD CONSTRAINT FK_699AF7D9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association_bucket_user_user DROP FOREIGN KEY FK_18AB1AC231937DC1');
        $this->addSql('ALTER TABLE association_bucket_user_user DROP FOREIGN KEY FK_18AB1AC2A76ED395');
        $this->addSql('ALTER TABLE association_bucket_user_bucket_entity DROP FOREIGN KEY FK_76B457F631937DC1');
        $this->addSql('ALTER TABLE association_bucket_user_bucket_entity DROP FOREIGN KEY FK_76B457F6AF3EC6C6');
        $this->addSql('ALTER TABLE bucket_entity DROP FOREIGN KEY FK_699AF7D912469DE2');
        $this->addSql('ALTER TABLE bucket_entity DROP FOREIGN KEY FK_699AF7D9F675F31B');
        $this->addSql('ALTER TABLE bucket_entity DROP FOREIGN KEY FK_699AF7D9A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE association_bucket_user');
        $this->addSql('DROP TABLE association_bucket_user_user');
        $this->addSql('DROP TABLE association_bucket_user_bucket_entity');
        $this->addSql('DROP TABLE bucket_entity');
        $this->addSql('DROP TABLE category_entity');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
