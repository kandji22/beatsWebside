<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529163615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE albums (id INT AUTO_INCREMENT NOT NULL, contrat_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F4E2474F1823061F (contrat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, file_contrat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instrumentals (id INT AUTO_INCREMENT NOT NULL, album_id_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, fichier_audio VARCHAR(255) NOT NULL, nombres_likes INT DEFAULT NULL, INDEX IDX_DCA0A4849FCD471 (album_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, instrumental_id_id INT NOT NULL, user_id_id INT NOT NULL, like_instrumental TINYINT(1) NOT NULL, INDEX IDX_49CA4E7D7DB01469 (instrumental_id_id), INDEX IDX_49CA4E7D9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, id_album_id INT DEFAULT NULL, fullnameuser VARCHAR(255) DEFAULT NULL, createdat DATETIME NOT NULL, nombre_album INT NOT NULL, email VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, stripesession VARCHAR(255) DEFAULT NULL, price INT DEFAULT NULL, INDEX IDX_ED896F4641EC475A (id_album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchases (id INT AUTO_INCREMENT NOT NULL, utilisateur_id_id INT NOT NULL, album_id_id INT NOT NULL, date_purchases DATE NOT NULL, INDEX IDX_AA6431FEB981C689 (utilisateur_id_id), UNIQUE INDEX UNIQ_AA6431FE9FCD471 (album_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unsubscribe (id INT AUTO_INCREMENT NOT NULL, utilisateur_id_id INT NOT NULL, date_unsubscribe DATE NOT NULL, INDEX IDX_A56C300EB981C689 (utilisateur_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, addresse VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(255) DEFAULT NULL, pays VARCHAR(255) DEFAULT NULL, date_inscription DATE NOT NULL, picture VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE albums ADD CONSTRAINT FK_F4E2474F1823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE instrumentals ADD CONSTRAINT FK_DCA0A4849FCD471 FOREIGN KEY (album_id_id) REFERENCES albums (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D7DB01469 FOREIGN KEY (instrumental_id_id) REFERENCES instrumentals (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D9D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F4641EC475A FOREIGN KEY (id_album_id) REFERENCES albums (id)');
        $this->addSql('ALTER TABLE purchases ADD CONSTRAINT FK_AA6431FEB981C689 FOREIGN KEY (utilisateur_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE purchases ADD CONSTRAINT FK_AA6431FE9FCD471 FOREIGN KEY (album_id_id) REFERENCES albums (id)');
        $this->addSql('ALTER TABLE unsubscribe ADD CONSTRAINT FK_A56C300EB981C689 FOREIGN KEY (utilisateur_id_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrumentals DROP FOREIGN KEY FK_DCA0A4849FCD471');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F4641EC475A');
        $this->addSql('ALTER TABLE purchases DROP FOREIGN KEY FK_AA6431FE9FCD471');
        $this->addSql('ALTER TABLE albums DROP FOREIGN KEY FK_F4E2474F1823061F');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D7DB01469');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D9D86650F');
        $this->addSql('ALTER TABLE purchases DROP FOREIGN KEY FK_AA6431FEB981C689');
        $this->addSql('ALTER TABLE unsubscribe DROP FOREIGN KEY FK_A56C300EB981C689');
        $this->addSql('DROP TABLE albums');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE instrumentals');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE order_detail');
        $this->addSql('DROP TABLE purchases');
        $this->addSql('DROP TABLE unsubscribe');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
