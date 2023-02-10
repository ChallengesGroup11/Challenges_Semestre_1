<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210020934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driving_school DROP CONSTRAINT fk_c3b2407b3da5256d');
        $this->addSql('DROP INDEX idx_c3b2407b3da5256d');
        $this->addSql('ALTER TABLE driving_school ADD file_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE driving_school DROP image_id');
        $this->addSql('ALTER TABLE driving_school DROP url_kbis');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE driving_school ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE driving_school ADD url_kbis VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE driving_school DROP file_path');
        $this->addSql('ALTER TABLE driving_school ADD CONSTRAINT fk_c3b2407b3da5256d FOREIGN KEY (image_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c3b2407b3da5256d ON driving_school (image_id)');
    }
}
