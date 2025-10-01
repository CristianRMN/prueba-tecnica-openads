<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930103149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tarifa (id INT AUTO_INCREMENT NOT NULL, proovedor_id_id INT NOT NULL, medio_id_id INT NOT NULL, precio NUMERIC(10, 2) NOT NULL, moneda VARCHAR(3) NOT NULL, vigente_desde DATE NOT NULL, vigente_hasta DATE DEFAULT NULL, INDEX IDX_A01B5DEBF9F75E0 (proovedor_id_id), INDEX IDX_A01B5DE306221BB (medio_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tarifa ADD CONSTRAINT FK_A01B5DEBF9F75E0 FOREIGN KEY (proovedor_id_id) REFERENCES proovedor (id)');
        $this->addSql('ALTER TABLE tarifa ADD CONSTRAINT FK_A01B5DE306221BB FOREIGN KEY (medio_id_id) REFERENCES medio (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarifa DROP FOREIGN KEY FK_A01B5DEBF9F75E0');
        $this->addSql('ALTER TABLE tarifa DROP FOREIGN KEY FK_A01B5DE306221BB');
        $this->addSql('DROP TABLE tarifa');
    }
}
