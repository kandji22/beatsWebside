<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526123413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE albums ADD status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE albums ADD CONSTRAINT FK_F4E2474F1823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F4E2474F1823061F ON albums (contrat_id)');
        $this->addSql('ALTER TABLE albums RENAME INDEX fk_f4e2474f64577843 TO IDX_F4E2474F64577843');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE albums DROP FOREIGN KEY FK_F4E2474F1823061F');
        $this->addSql('DROP INDEX UNIQ_F4E2474F1823061F ON albums');
        $this->addSql('ALTER TABLE albums DROP status');
        $this->addSql('ALTER TABLE albums RENAME INDEX idx_f4e2474f64577843 TO FK_F4E2474F64577843');
    }
}
