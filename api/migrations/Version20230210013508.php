<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210013508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE media_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media_object (id INT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE driving_school ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE driving_school ADD CONSTRAINT FK_C3B2407B3DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C3B2407B3DA5256D ON driving_school (image_id)');
        $this->addSql('ALTER TABLE "user" ALTER status DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE driving_school DROP CONSTRAINT FK_C3B2407B3DA5256D');
        $this->addSql('DROP SEQUENCE media_object_id_seq CASCADE');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('ALTER TABLE "user" ALTER status SET NOT NULL');
        $this->addSql('DROP INDEX IDX_C3B2407B3DA5256D');
        $this->addSql('ALTER TABLE driving_school DROP image_id');
    }
}
