<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019190218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jugador (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, habilidad INT NOT NULL, tipo VARCHAR(255) NOT NULL, tiempo_reaccion INT DEFAULT NULL, fuerza INT DEFAULT NULL, velocidad INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partido (id INT AUTO_INCREMENT NOT NULL, jugador1_id INT NOT NULL, jugador2_id INT NOT NULL, ganador_id INT NOT NULL, INDEX IDX_4E79750B390198F4 (jugador1_id), INDEX IDX_4E79750B2BB4371A (jugador2_id), INDEX IDX_4E79750BA338CEA5 (ganador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE torneo (id INT AUTO_INCREMENT NOT NULL, rondas VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B390198F4 FOREIGN KEY (jugador1_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B2BB4371A FOREIGN KEY (jugador2_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750BA338CEA5 FOREIGN KEY (ganador_id) REFERENCES jugador (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B390198F4');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B2BB4371A');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750BA338CEA5');
        $this->addSql('DROP TABLE jugador');
        $this->addSql('DROP TABLE partido');
        $this->addSql('DROP TABLE torneo');
    }
}
