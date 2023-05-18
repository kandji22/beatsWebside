<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509133414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchases (id INT AUTO_INCREMENT NOT NULL, utilisateur_id_id INT NOT NULL, album_id_id INT NOT NULL, date_purchases DATE NOT NULL, INDEX IDX_AA6431FEB981C689 (utilisateur_id_id), UNIQUE INDEX UNIQ_AA6431FE9FCD471 (album_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unsubscribe (id INT AUTO_INCREMENT NOT NULL, utilisateur_id_id INT NOT NULL, date_unsubscribe DATE NOT NULL, INDEX IDX_A56C300EB981C689 (utilisateur_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchases ADD CONSTRAINT FK_AA6431FEB981C689 FOREIGN KEY (utilisateur_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE purchases ADD CONSTRAINT FK_AA6431FE9FCD471 FOREIGN KEY (album_id_id) REFERENCES albums (id)');
        $this->addSql('ALTER TABLE unsubscribe ADD CONSTRAINT FK_A56C300EB981C689 FOREIGN KEY (utilisateur_id_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE purchases');
        $this->addSql('DROP TABLE unsubscribe');
    }
}
