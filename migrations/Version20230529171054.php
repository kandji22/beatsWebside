<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529171054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F4641EC475A');
        $this->addSql('DROP INDEX IDX_ED896F4641EC475A ON order_detail');
        $this->addSql('ALTER TABLE order_detail ADD quantity INT NOT NULL, DROP id_album_id, DROP fullnameuser, DROP createdat, DROP email, DROP status, DROP stripesession, CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE nombre_album album_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F461137ABCF FOREIGN KEY (album_id) REFERENCES albums (id)');
        $this->addSql('CREATE INDEX IDX_ED896F461137ABCF ON order_detail (album_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F461137ABCF');
        $this->addSql('DROP INDEX IDX_ED896F461137ABCF ON order_detail');
        $this->addSql('ALTER TABLE order_detail ADD id_album_id INT DEFAULT NULL, ADD fullnameuser VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD createdat DATETIME NOT NULL, ADD nombre_album INT NOT NULL, ADD email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD status TINYINT(1) DEFAULT NULL, ADD stripesession VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP album_id, DROP quantity, CHANGE price price INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F4641EC475A FOREIGN KEY (id_album_id) REFERENCES albums (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_ED896F4641EC475A ON order_detail (id_album_id)');
    }
}
