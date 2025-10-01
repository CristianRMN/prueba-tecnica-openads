<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930102233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contenido (id INT AUTO_INCREMENT NOT NULL, medio_id INT NOT NULL, proovedor_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, cuerpo LONGTEXT NOT NULL, tipo_contenido VARCHAR(255) NOT NULL, num_enlaces INT NOT NULL, longitud_palabras INT NOT NULL, categoria_publicar VARCHAR(255) NOT NULL, url_publicacion VARCHAR(255) DEFAULT NULL, fecha_publicacion DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', precio_aplicado NUMERIC(10, 2) NOT NULL, moneda VARCHAR(3) NOT NULL, fecha_pago DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D0A7397FA40AA46 (medio_id), INDEX IDX_D0A7397F39679F91 (proovedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contenido ADD CONSTRAINT FK_D0A7397FA40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id)');
        $this->addSql('ALTER TABLE contenido ADD CONSTRAINT FK_D0A7397F39679F91 FOREIGN KEY (proovedor_id) REFERENCES proovedor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenido DROP FOREIGN KEY FK_D0A7397FA40AA46');
        $this->addSql('ALTER TABLE contenido DROP FOREIGN KEY FK_D0A7397F39679F91');
        $this->addSql('DROP TABLE contenido');
    }
}
