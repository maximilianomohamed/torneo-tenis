<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019222543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE torneo ADD ganador_id INT DEFAULT NULL, ADD fecha_torneo DATETIME NOT NULL, ADD genero VARCHAR(255) NOT NULL, CHANGE rondas nombre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE torneo ADD CONSTRAINT FK_7CEB63FEA338CEA5 FOREIGN KEY (ganador_id) REFERENCES jugador (id)');
        $this->addSql('CREATE INDEX IDX_7CEB63FEA338CEA5 ON torneo (ganador_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE torneo DROP FOREIGN KEY FK_7CEB63FEA338CEA5');
        $this->addSql('DROP INDEX IDX_7CEB63FEA338CEA5 ON torneo');
        $this->addSql('ALTER TABLE torneo ADD rondas VARCHAR(255) NOT NULL, DROP ganador_id, DROP nombre, DROP fecha_torneo, DROP genero');
    }
}
