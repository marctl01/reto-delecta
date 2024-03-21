<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240317190823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurante (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, street_address VARCHAR(255) NOT NULL, latitude NUMERIC(10, 6) NOT NULL, longitude NUMERIC(10, 6) NOT NULL, city_name VARCHAR(255) NOT NULL, popularity_rate NUMERIC(5, 2) NOT NULL, satisfaction_rate NUMERIC(5, 2) NOT NULL, last_avg_price NUMERIC(8, 2) NOT NULL, total_reviews INT NOT NULL, uidentifier VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurante_segmento (restaurante_id INT NOT NULL, segmento_id INT NOT NULL, INDEX IDX_534B8AD038B81E49 (restaurante_id), INDEX IDX_534B8AD05502A33D (segmento_id), PRIMARY KEY(restaurante_id, segmento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE segmento (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, size INT NOT NULL, uidentifier VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurante_segmento ADD CONSTRAINT FK_534B8AD038B81E49 FOREIGN KEY (restaurante_id) REFERENCES restaurante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurante_segmento ADD CONSTRAINT FK_534B8AD05502A33D FOREIGN KEY (segmento_id) REFERENCES segmento (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurante_segmento DROP FOREIGN KEY FK_534B8AD038B81E49');
        $this->addSql('ALTER TABLE restaurante_segmento DROP FOREIGN KEY FK_534B8AD05502A33D');
        $this->addSql('DROP TABLE restaurante');
        $this->addSql('DROP TABLE restaurante_segmento');
        $this->addSql('DROP TABLE segmento');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
