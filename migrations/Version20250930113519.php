<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930113519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenido DROP FOREIGN KEY FK_D0A7397F39679F91');
        $this->addSql('ALTER TABLE contenido DROP FOREIGN KEY FK_D0A7397FA40AA46');
        $this->addSql('DROP INDEX IDX_D0A7397F39679F91 ON contenido');
        $this->addSql('DROP INDEX IDX_D0A7397FA40AA46 ON contenido');
        $this->addSql('ALTER TABLE contenido ADD medio_id_id INT NOT NULL, ADD proovedor_id_id INT NOT NULL, DROP medio_id, DROP proovedor_id');
        $this->addSql('ALTER TABLE contenido ADD CONSTRAINT FK_D0A7397F306221BB FOREIGN KEY (medio_id_id) REFERENCES medio (id)');
        $this->addSql('ALTER TABLE contenido ADD CONSTRAINT FK_D0A7397FBF9F75E0 FOREIGN KEY (proovedor_id_id) REFERENCES proovedor (id)');
        $this->addSql('CREATE INDEX IDX_D0A7397F306221BB ON contenido (medio_id_id)');
        $this->addSql('CREATE INDEX IDX_D0A7397FBF9F75E0 ON contenido (proovedor_id_id)');
        $this->addSql('ALTER TABLE enlace ADD contenido_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE enlace ADD CONSTRAINT FK_8414B279FE0C03A4 FOREIGN KEY (contenido_id_id) REFERENCES contenido (id)');
        $this->addSql('CREATE INDEX IDX_8414B279FE0C03A4 ON enlace (contenido_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D948C0B17C04B7F ON medio (dominio)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenido DROP FOREIGN KEY FK_D0A7397F306221BB');
        $this->addSql('ALTER TABLE contenido DROP FOREIGN KEY FK_D0A7397FBF9F75E0');
        $this->addSql('DROP INDEX IDX_D0A7397F306221BB ON contenido');
        $this->addSql('DROP INDEX IDX_D0A7397FBF9F75E0 ON contenido');
        $this->addSql('ALTER TABLE contenido ADD medio_id INT NOT NULL, ADD proovedor_id INT NOT NULL, DROP medio_id_id, DROP proovedor_id_id');
        $this->addSql('ALTER TABLE contenido ADD CONSTRAINT FK_D0A7397F39679F91 FOREIGN KEY (proovedor_id) REFERENCES proovedor (id)');
        $this->addSql('ALTER TABLE contenido ADD CONSTRAINT FK_D0A7397FA40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id)');
        $this->addSql('CREATE INDEX IDX_D0A7397F39679F91 ON contenido (proovedor_id)');
        $this->addSql('CREATE INDEX IDX_D0A7397FA40AA46 ON contenido (medio_id)');
        $this->addSql('ALTER TABLE enlace DROP FOREIGN KEY FK_8414B279FE0C03A4');
        $this->addSql('DROP INDEX IDX_8414B279FE0C03A4 ON enlace');
        $this->addSql('ALTER TABLE enlace DROP contenido_id_id');
        $this->addSql('DROP INDEX UNIQ_8D948C0B17C04B7F ON medio');
    }
}
