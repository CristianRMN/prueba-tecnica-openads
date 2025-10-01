<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930093122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medio (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, dominio VARCHAR(255) NOT NULL, categoria VARCHAR(100) NOT NULL, tematicas_delicadas LONGTEXT DEFAULT NULL, num_enlaces_permitidos INT NOT NULL, tipo_enlace_permitido VARCHAR(255) NOT NULL, publica_portada TINYINT(1) NOT NULL, publica_categorias TINYINT(1) NOT NULL, tematicas_no_aceptadas LONGTEXT DEFAULT NULL, indica_patrocinado TINYINT(1) NOT NULL, trafico_mes INT NOT NULL, da INT DEFAULT NULL, dr INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE medio');
    }
}
