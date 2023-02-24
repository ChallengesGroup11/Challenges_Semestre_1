<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224215000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE driving_school_booking');
        $this->addSql('ALTER TABLE booking ADD driving_school_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEE8F87988 FOREIGN KEY (driving_school_id_id) REFERENCES driving_school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E00CEDDEE8F87988 ON booking (driving_school_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE driving_school_booking (driving_school_id INT NOT NULL, booking_id INT NOT NULL, PRIMARY KEY(driving_school_id, booking_id))');
        $this->addSql('CREATE INDEX idx_d8c2d469ebf3dfe7 ON driving_school_booking (driving_school_id)');
        $this->addSql('CREATE INDEX idx_d8c2d4693301c60 ON driving_school_booking (booking_id)');
        $this->addSql('ALTER TABLE driving_school_booking ADD CONSTRAINT fk_d8c2d469ebf3dfe7 FOREIGN KEY (driving_school_id) REFERENCES driving_school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE driving_school_booking ADD CONSTRAINT fk_d8c2d4693301c60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDEE8F87988');
        $this->addSql('DROP INDEX IDX_E00CEDDEE8F87988');
        $this->addSql('ALTER TABLE booking DROP driving_school_id_id');
    }
}
