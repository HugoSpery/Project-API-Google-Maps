<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617165847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE history DROP street');
        $this->addSql('ALTER TABLE history ALTER id TYPE INT');
        $this->addSql('ALTER TABLE history ALTER city SET NOT NULL');
        $this->addSql('ALTER TABLE history ALTER city TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE history ALTER postal_code TYPE INT');
        $this->addSql('ALTER TABLE history ALTER coordinate_x TYPE INT');
        $this->addSql('ALTER TABLE history ALTER coordinate_x SET NOT NULL');
        $this->addSql('ALTER TABLE history ALTER coordinate_y TYPE INT');
        $this->addSql('ALTER TABLE history ALTER coordinate_y SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE history_id_seq CASCADE');
        $this->addSql('ALTER TABLE history ADD street VARCHAR(200) DEFAULT NULL');
        $this->addSql('ALTER TABLE history ALTER id TYPE NUMERIC(10, 0)');
        $this->addSql('ALTER TABLE history ALTER city DROP NOT NULL');
        $this->addSql('ALTER TABLE history ALTER city TYPE VARCHAR(200)');
        $this->addSql('ALTER TABLE history ALTER postal_code TYPE NUMERIC(5, 0)');
        $this->addSql('ALTER TABLE history ALTER coordinate_x TYPE NUMERIC(10, 0)');
        $this->addSql('ALTER TABLE history ALTER coordinate_x DROP NOT NULL');
        $this->addSql('ALTER TABLE history ALTER coordinate_y TYPE NUMERIC(10, 0)');
        $this->addSql('ALTER TABLE history ALTER coordinate_y DROP NOT NULL');
    }
}
