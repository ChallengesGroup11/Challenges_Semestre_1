<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213183250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE director_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE driving_school_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE monitor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE package_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE student_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, slot_begin TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, slot_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comment VARCHAR(255) DEFAULT NULL, status_validate BOOLEAN NOT NULL, status_done BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE booking_student (booking_id INT NOT NULL, student_id INT NOT NULL, PRIMARY KEY(booking_id, student_id))');
        $this->addSql('CREATE INDEX IDX_ED9DEA133301C60 ON booking_student (booking_id)');
        $this->addSql('CREATE INDEX IDX_ED9DEA13CB944F1A ON booking_student (student_id)');
        $this->addSql('CREATE TABLE booking_monitor (booking_id INT NOT NULL, monitor_id INT NOT NULL, PRIMARY KEY(booking_id, monitor_id))');
        $this->addSql('CREATE INDEX IDX_BBABDCA53301C60 ON booking_monitor (booking_id)');
        $this->addSql('CREATE INDEX IDX_BBABDCA54CE1C902 ON booking_monitor (monitor_id)');
        $this->addSql('CREATE TABLE booking_driving_school (booking_id INT NOT NULL, driving_school_id INT NOT NULL, PRIMARY KEY(booking_id, driving_school_id))');
        $this->addSql('CREATE INDEX IDX_58DDBB8B3301C60 ON booking_driving_school (booking_id)');
        $this->addSql('CREATE INDEX IDX_58DDBB8BEBF3DFE7 ON booking_driving_school (driving_school_id)');
        $this->addSql('CREATE TABLE director (id INT NOT NULL, user_id_id INT DEFAULT NULL, driving_school_id_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1E90D3F09D86650F ON director (user_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1E90D3F0E8F87988 ON director (driving_school_id_id)');
        $this->addSql('CREATE TABLE driving_school (id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zipcode VARCHAR(5) NOT NULL, siret VARCHAR(14) NOT NULL, phone_number VARCHAR(10) NOT NULL, file_path VARCHAR(255) DEFAULT NULL, status BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE monitor (id INT NOT NULL, user_id_id INT DEFAULT NULL, driving_school_id_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E11599859D86650F ON monitor (user_id_id)');
        $this->addSql('CREATE INDEX IDX_E1159985E8F87988 ON monitor (driving_school_id_id)');
        $this->addSql('CREATE TABLE package (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, nb_credit INT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE payment (id INT NOT NULL, package_id_id INT NOT NULL, user_id_id INT NOT NULL, date_action DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D28840DEA4099B0 ON payment (package_id_id)');
        $this->addSql('CREATE INDEX IDX_6D28840D9D86650F ON payment (user_id_id)');
        $this->addSql('CREATE TABLE student (id INT NOT NULL, user_id_id INT DEFAULT NULL, nb_hour_done INT DEFAULT NULL, file_path_code VARCHAR(255) DEFAULT NULL, file_path_cni VARCHAR(255) DEFAULT NULL, count_credit INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF339D86650F ON student (user_id_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, status BOOLEAN DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE booking_student ADD CONSTRAINT FK_ED9DEA133301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_student ADD CONSTRAINT FK_ED9DEA13CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_monitor ADD CONSTRAINT FK_BBABDCA53301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_monitor ADD CONSTRAINT FK_BBABDCA54CE1C902 FOREIGN KEY (monitor_id) REFERENCES monitor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_driving_school ADD CONSTRAINT FK_58DDBB8B3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_driving_school ADD CONSTRAINT FK_58DDBB8BEBF3DFE7 FOREIGN KEY (driving_school_id) REFERENCES driving_school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT FK_1E90D3F09D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT FK_1E90D3F0E8F87988 FOREIGN KEY (driving_school_id_id) REFERENCES driving_school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E11599859D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E1159985E8F87988 FOREIGN KEY (driving_school_id_id) REFERENCES driving_school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DEA4099B0 FOREIGN KEY (package_id_id) REFERENCES package (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF339D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE booking_student DROP CONSTRAINT FK_ED9DEA133301C60');
        $this->addSql('ALTER TABLE booking_monitor DROP CONSTRAINT FK_BBABDCA53301C60');
        $this->addSql('ALTER TABLE booking_driving_school DROP CONSTRAINT FK_58DDBB8B3301C60');
        $this->addSql('ALTER TABLE booking_driving_school DROP CONSTRAINT FK_58DDBB8BEBF3DFE7');
        $this->addSql('ALTER TABLE director DROP CONSTRAINT FK_1E90D3F0E8F87988');
        $this->addSql('ALTER TABLE monitor DROP CONSTRAINT FK_E1159985E8F87988');
        $this->addSql('ALTER TABLE booking_monitor DROP CONSTRAINT FK_BBABDCA54CE1C902');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840DEA4099B0');
        $this->addSql('ALTER TABLE booking_student DROP CONSTRAINT FK_ED9DEA13CB944F1A');
        $this->addSql('ALTER TABLE director DROP CONSTRAINT FK_1E90D3F09D86650F');
        $this->addSql('ALTER TABLE monitor DROP CONSTRAINT FK_E11599859D86650F');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D9D86650F');
        $this->addSql('ALTER TABLE student DROP CONSTRAINT FK_B723AF339D86650F');
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE director_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE driving_school_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE monitor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE package_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE student_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE booking_student');
        $this->addSql('DROP TABLE booking_monitor');
        $this->addSql('DROP TABLE booking_driving_school');
        $this->addSql('DROP TABLE director');
        $this->addSql('DROP TABLE driving_school');
        $this->addSql('DROP TABLE monitor');
        $this->addSql('DROP TABLE package');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE "user"');
    }
}
