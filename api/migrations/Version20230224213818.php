<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224213818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE driving_school_booking (driving_school_id INT NOT NULL, booking_id INT NOT NULL, PRIMARY KEY(driving_school_id, booking_id))');
        $this->addSql('CREATE INDEX IDX_D8C2D469EBF3DFE7 ON driving_school_booking (driving_school_id)');
        $this->addSql('CREATE INDEX IDX_D8C2D4693301C60 ON driving_school_booking (booking_id)');
        $this->addSql('ALTER TABLE driving_school_booking ADD CONSTRAINT FK_D8C2D469EBF3DFE7 FOREIGN KEY (driving_school_id) REFERENCES driving_school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE driving_school_booking ADD CONSTRAINT FK_D8C2D4693301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE booking_driving_school');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE booking_driving_school (booking_id INT NOT NULL, driving_school_id INT NOT NULL, PRIMARY KEY(booking_id, driving_school_id))');
        $this->addSql('CREATE INDEX idx_58ddbb8bebf3dfe7 ON booking_driving_school (driving_school_id)');
        $this->addSql('CREATE INDEX idx_58ddbb8b3301c60 ON booking_driving_school (booking_id)');
        $this->addSql('ALTER TABLE booking_driving_school ADD CONSTRAINT fk_58ddbb8b3301c60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_driving_school ADD CONSTRAINT fk_58ddbb8bebf3dfe7 FOREIGN KEY (driving_school_id) REFERENCES driving_school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE driving_school_booking');
    }
}
