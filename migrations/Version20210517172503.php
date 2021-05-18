<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517172503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bar (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, bar_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_4E10122D89A253A (bar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comanda (id INT AUTO_INCREMENT NOT NULL, taula_id INT DEFAULT NULL, bar_id INT DEFAULT NULL, acabat TINYINT(1) NOT NULL, hora_acabat DATETIME NOT NULL, preu_total DOUBLE PRECISION NOT NULL, INDEX IDX_45C50E547D4E8D28 (taula_id), INDEX IDX_45C50E5489A253A (bar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comanda_producte (comanda_id INT NOT NULL, producte_id INT NOT NULL, INDEX IDX_922A83D0787958A8 (comanda_id), INDEX IDX_922A83D019F889EA (producte_id), PRIMARY KEY(comanda_id, producte_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producte (id INT AUTO_INCREMENT NOT NULL, categoria_id INT DEFAULT NULL, bar_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, preu DOUBLE PRECISION NOT NULL, disponible TINYINT(1) NOT NULL, INDEX IDX_476EEF0B3397707A (categoria_id), INDEX IDX_476EEF0B89A253A (bar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taula (id INT AUTO_INCREMENT NOT NULL, bar_id INT DEFAULT NULL, identificador VARCHAR(255) NOT NULL, ocupada TINYINT(1) NOT NULL, INDEX IDX_E82DFEAA89A253A (bar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categoria ADD CONSTRAINT FK_4E10122D89A253A FOREIGN KEY (bar_id) REFERENCES bar (id)');
        $this->addSql('ALTER TABLE comanda ADD CONSTRAINT FK_45C50E547D4E8D28 FOREIGN KEY (taula_id) REFERENCES taula (id)');
        $this->addSql('ALTER TABLE comanda ADD CONSTRAINT FK_45C50E5489A253A FOREIGN KEY (bar_id) REFERENCES bar (id)');
        $this->addSql('ALTER TABLE comanda_producte ADD CONSTRAINT FK_922A83D0787958A8 FOREIGN KEY (comanda_id) REFERENCES comanda (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comanda_producte ADD CONSTRAINT FK_922A83D019F889EA FOREIGN KEY (producte_id) REFERENCES producte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producte ADD CONSTRAINT FK_476EEF0B3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE producte ADD CONSTRAINT FK_476EEF0B89A253A FOREIGN KEY (bar_id) REFERENCES bar (id)');
        $this->addSql('ALTER TABLE taula ADD CONSTRAINT FK_E82DFEAA89A253A FOREIGN KEY (bar_id) REFERENCES bar (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoria DROP FOREIGN KEY FK_4E10122D89A253A');
        $this->addSql('ALTER TABLE comanda DROP FOREIGN KEY FK_45C50E5489A253A');
        $this->addSql('ALTER TABLE producte DROP FOREIGN KEY FK_476EEF0B89A253A');
        $this->addSql('ALTER TABLE taula DROP FOREIGN KEY FK_E82DFEAA89A253A');
        $this->addSql('ALTER TABLE producte DROP FOREIGN KEY FK_476EEF0B3397707A');
        $this->addSql('ALTER TABLE comanda_producte DROP FOREIGN KEY FK_922A83D0787958A8');
        $this->addSql('ALTER TABLE comanda_producte DROP FOREIGN KEY FK_922A83D019F889EA');
        $this->addSql('ALTER TABLE comanda DROP FOREIGN KEY FK_45C50E547D4E8D28');
        $this->addSql('DROP TABLE bar');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE comanda');
        $this->addSql('DROP TABLE comanda_producte');
        $this->addSql('DROP TABLE producte');
        $this->addSql('DROP TABLE taula');
    }
}
